<?php

/**
 * Apply BBCode
 * Options :
 * - $options['class'] string div class surrounding text
 * - $options['headers_max_level'] int maximal headers level
 * - $options['anchors_id'] string text identifier used for anchors
 * - $options['index_class'] string class for index
 * - $options['notes_class'] string class for notes
 * - $options['spoiler_class'] string class for spoilers
 * - $options['information_class'] string class for information
 * - $options['warning_class'] string class for warning
 * - $options['error_class'] string class for error
 * - $options['code_start_default'] string for code default start
 * - $options['code_start_language'] string for code start with a defined language
 * - $options['code_end'] string for code end
 * @license LGPL v3
 * @version 2013-10-04
 * @param $text string bbcode
 * @param $options array options
 * @return string html
 */
function bbcode($text, $options=null)
{
    // Replace < and >
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    
    // Process on options
    if (!isset($options['headers_max_level'])) {
        $options['headers_max_level'] = 0;
    }
    if (!isset($options['anchors_id'])) {
        $options['anchors_id'] = null;
    }
    if (!isset($options['index_class'])) {
        $options['index_class'] = 'index';
    }
    if (!isset($options['notes_class'])) {
        $options['notes_class'] = 'notes';
    }
    if (!isset($options['spoiler_class'])) {
        $options['spoiler_class'] = 'spoiler';
    }
    if (!isset($options['information_class'])) {
        $options['information_class'] = 'information';
    }
    if (!isset($options['warning_class'])) {
        $options['warning_class'] = 'warning';
    }
    if (!isset($options['error_class'])) {
        $options['error_class'] = 'error';
    }
    if (!isset($options['code_start_default'])) {
        $options['code_start_default'] = '<pre><code>';
    }
    if (!isset($options['code_start_language'])) {
        $options['code_start_language'] = '<pre><code class="$1">';
    }
    if (!isset($options['code_end'])) {
        $options['code_end'] = '</code></pre>';
    }

    // Build anchors
    $topAnchor = 'top';
    $indexAnchor = 'title';
    $notesAnchor = 'note';
    if ($options['anchors_id'] !== null) {
        $topAnchor .= $options['anchors_id'];
        $indexAnchor .= $options['anchors_id'];
        $notesAnchor .= $options['anchors_id'];
    }
    
    // Headers/Summary
    $headerPattern = '/\[h([1-4])\](.+?)\[\/h([1-4])\]/s';
    if (preg_match_all($headerPattern, $text, $results)) {
        // Headers
        $anchors = array();
        foreach ($results[0] as $key => $result) {
            // Length/Position
            $length = strlen($result);
            $position = strpos($text, $result);
            
            // Build title
            $title = strtolower($results[2][$key]);
            $title = preg_replace('/[^a-z0-9]+/', ' ', $title);
            $title = trim($title);
            $title = strtr($title, ' ', '_');
            
            // Build anchor
            $anchors[$key] = $indexAnchor.'-'.$title.'-'.$key;
            
            // Display top anchor ?
            $anchor = trim(substr($text, 0, $position)) != '';
            
            // Header level
            $headerLevel = $results[1][$key]+$options['headers_max_level'];
            
            // Build header
            $header = '<h'.$headerLevel.' id="'.$anchors[$key].'">'.
                      $results[2][$key].' '.
                      ($anchor ? '<a href="#'.$topAnchor.'">&uarr;</a>' : '').
                      '</h'.$headerLevel.'>';
            
            // Insert header
            $text = substr($text, 0, $position).
                    $header.
                    substr($text, $position+$length);
        }
        
        // Index
        if (strpos($text, '[index]') !== false) {
            // Init index
            $index = '';
            
            // Construct index
            $open = 0;
            foreach ($anchors as $key => $anchor) {
                if ($key != 0) {
                    // Check level
                    $diff = $results[1][$key-1] - $results[1][$key];
                    if ($diff > 0) { // Level up
                        $index .= '</li></ul></li>';
                        $open--;
                        for ($i=1;$i<$diff;$i++) {
                            $index .= '</ul></li>';
                            $open--;
                        }
                    } else if ($diff < 0) { // Level down
                        $index .= '<ul>';
                        $open++;
                        for ($i=-1;$i>$diff;$i--) {
                            $index .= '<li><ul>';
                            $open++;
                        }
                    } else { // Same level
                        $index .= '</li>';
                    }
                }
                $index .= '<li><a href="#'.$anchor.'">'.
                          $results[2][$key].
                          '</a>';
            }
            
            // Close index
            if ($open > 0) {
                while ($open > 0) {
                    $index .= '</li></ul>';
                    $open--;
                }
            }
            $index .= '</li>';
            
            // Index class
            $indexClass = '';
            if ($options['index_class'] !== null) {
                $indexClass = ' class="'.$options['index_class'].'"';
            }
            
            // Insert index
            $text = str_replace(
                '[index]',
                '<ul'.$indexClass.'>'.$index.'</ul>',
                $text
            );
        }
    }
    
    // Bold texts
    $text = str_replace('[b]', '<b>', $text);
    $text = str_replace('[/b]', '</b>', $text);
    
    // Italic texts
    $text = str_replace('[i]', '<i>', $text);
    $text = str_replace('[/i]', '</i>', $text);
    
    // Underline texts
    $text = str_replace('[u]', '<u>', $text);
    $text = str_replace('[/u]', '</u>', $text);
    
    // Strikes texts
    $text = str_replace('[s]', '<s>', $text);
    $text = str_replace('[/s]', '</s>', $text);
    
    // Colored texts
    $text = preg_replace(
        '/\[color="?([^"]*?)"?\](.*?)\[\/color\]/',
        '<span style="color:$1;">$2</span>',
        $text
    );
    
    // Links
    $text = preg_replace(
        '/\[url\](.*?)\[\/url\]/',
        '<a href="$1">$1</a>',
        $text
    );
    $text = preg_replace(
        '/\[url="?([^"]*?)"?\](.*?)\[\/url\]/',
        '<a href="$1">$2</a>',
        $text
    );
    
    // Emails
    $text = preg_replace(
        '/\[email\](.*?)\[\/email\]/',
        '<a href="mailto:$1">$1</a>',
        $text
    );
    $text = preg_replace(
        '/\[email="?([^"]*?)"?\](.*?)\[\/email\]/',
        '<a href="mailto:$1">$2</a>',
        $text
    );
    
    // Images
    $text = preg_replace(
        '/\[img\](.*?)\[\/img\]/',
        '<img src="$1" alt="#" />',
        $text
    );
    $text = preg_replace(
        '/\[img="?([^"]*?)"?\](.*?)\[\/img\]/',
        '<img src="$2" alt="$1" />',
        $text
    );
    
    // Image placement
    $pattern = '/\[imgs-left\](.*?)\<img src(.*?)\[\/imgs-left\]/s';
    while (preg_match($pattern, $text)) {
        $text = preg_replace(
            $pattern,
            '$1<img style="float:left;" src$2',
            $text
        );
    }
    $pattern = '/\[imgs-right\](.*?)\<img src(.*?)\[\/imgs-right\]/s';
    while (preg_match($pattern, $text)) {
        $text = preg_replace(
            $pattern,
            '$1<img style="float:right;" src$2',
            $text
        );
    }
    $text = str_replace('[imgs-left]', '', $text);
    $text = str_replace('[/imgs-left]', '', $text);
    $text = str_replace('[imgs-right]', '', $text);
    $text = str_replace('[/imgs-right]', '', $text);
    
    // Codes
    $text = str_replace('[code]', $options['code_start_default'], $text);
    $text = preg_replace(
        '/\[code="?([^"]*?)"?\]/',
        $options['code_start_language'],
        $text
    );
    $text = str_replace('[/code]', $options['code_end'], $text);
    
    // Quotes
    $text = preg_replace('/\[quote\](.*?)\[\/quote\]/', '<q>$1</q>', $text);
    $text = preg_replace(
        '/\[quote="?([^"]*?)"?\](.*?)\[\/quote\]/',
        '$1 : <q>$2</q>',
        $text
    );
    
    // Alignements
    $text = str_replace('[left]', '<p style="text-align:left;">', $text);
    $text = str_replace('[/left]', '</p>', $text);
    $text = str_replace('[center]', '<p style="text-align:center;">', $text);
    $text = str_replace('[/center]', '</p>', $text);
    $text = str_replace('[right]', '<p style="text-align:right;">', $text);
    $text = str_replace('[/right]', '</p>', $text);
    $text = str_replace('[justify]', '<p style="text-align:justify;">', $text);
    $text = str_replace('[/justify]', '</p>', $text);
    
    // Indents
    $text = str_replace('[indent]', '<p style="padding-left:20px;">', $text);
    $text = str_replace('[/indent]', '</p>', $text);
    
    // Paragraphs
    $text = str_replace('[p]', '<p>', $text);
    $text = str_replace('[/p]', '</p>', $text);
    
    // Spoilers
    $text = str_replace(
        '[spoiler]',
        '<div class="'.$options['spoiler_class'].'"><p><a href="#" onclick="'.
        'if(this.innerHTML != \'+\') { '.
        'this.innerHTML = \'+\'; '.
        'this.parentNode.parentNode.childNodes[1].style.display = \'none\'; '.
        '} else { '.
        'this.innerHTML = \'-\'; '.
        'this.parentNode.parentNode.childNodes[1].style.display = \'block\'; '.
        '} '.
        'return false;'.
        '">+</a></p><p style="display:none;">',
        $text
    );
    $text = preg_replace(
        '/\[spoiler="?([^"]*?)"?\]/',
        '<div class="'.$options['spoiler_class'].'"><p><a href="#" onclick="'.
        'if (this.innerHTML != \'+ $1\') { '.
        'this.innerHTML = \'+ $1\'; '.
        'this.parentNode.parentNode.childNodes[1].style.display = \'none\'; '.
        '} else { '.
        'this.innerHTML = \'- $1\'; '.
        'this.parentNode.parentNode.childNodes[1].style.display = \'block\'; '.
        '} '.
        'return false;'.
        '">+ $1</a></p><p style="display:none;">',
        $text
    );
    $text = preg_replace(
        '/\[spoiler="?([^"]*?),([^"]*?)"?\]/',
        '<div class="'.$options['spoiler_class'].'"><p><a href="#" onclick="'.
        'if (this.innerHTML != \'+ $1\') { '.
        'this.innerHTML = \'+ $1\'; '.
        'this.parentNode.parentNode.childNodes[1].style.display = \'none\'; '.
        '} else {  this.innerHTML = \'- $2\'; '.
        'this.parentNode.parentNode.childNodes[1].style.display = \'block\'; '.
        '} '.
        'return false;'.
        '">+ $1</a></p><p style="display:none;">',
        $text
    );
    $text = str_replace('[/spoiler]', '</p></div>', $text);
    
    // Lines
    $text = str_replace('[hr]', '<hr/>', $text);
    
    // Errors/Warnings/Informations
    $informationClass = '';
    if ($options['information_class'] !== null) {
        $informationClass = ' class="'.$options['information_class'].'"';
    }
    $text = str_replace('[information]', '<p'.$informationClass.'>', $text);
    $text = str_replace('[/information]', '</p>', $text);
    $warningClass = '';
    if ($options['warning_class'] !== null) {
        $warningClass = ' class="'.$options['warning_class'].'"';
    }
    $text = str_replace('[warning]', '<p'.$warningClass.'>', $text);
    $text = str_replace('[/warning]', '</p>', $text);
    $errorClass = '';
    if ($options['error_class'] !== null) {
        $errorClass = ' class="'.$options['error_class'].'"';
    }
    $text = str_replace('[error]', '<p'.$errorClass.'>', $text);
    $text = str_replace('[/error]', '</p>', $text);
    
    // Tooltips
    $text = preg_replace(
        '/\[tooltip\](.*?)\[\/tooltip\]/',
        '<i title="$1">[?]</i>',
        $text
    );
    $text = preg_replace(
        '/\[tooltip="([^"]*?)"\](.*?)\[\/tooltip\]/',
        '<i title="$1">$2</i>',
        $text
    );
    
    // Indices/Exposants
    $text = str_replace('[sub]', '<sub>', $text);
    $text = str_replace('[/sub]', '</sub>', $text);
    $text = str_replace('[sup]', '<sup>', $text);
    $text = str_replace('[/sup]', '</sup>', $text);
    
    // Lists
    $text = preg_replace('/\[list\](.*?)\[\/list\]/s', '<ul>$1</ul>', $text);
    $text = preg_replace(
        '/\[list="?1"?\](.*?)\[\/list\]/s',
        '<ol style="list-style-type:decimal;">$1</ol>',
        $text
    );
    $text = preg_replace(
        '/\[list="?a"?\](.*?)\[\/list\]/s',
        '<ol style="list-style-type:lower-alpha;">$1</ol>',
        $text
    );
    $text = str_replace('[*]', "\n".'[*]', $text);
    $text = preg_replace('/\[\*\](.*)/', '<li>$1</li>', $text);
    
    // Notes
    $notePattern = '/\[note(="?([^"]*?)"?)?\](.+?)\[\/note\]/s';
    if (preg_match_all($notePattern, $text, $results)) {
        // Init notes
        $notes = '';
        
        // Note class
        $noteClass = '';
        if ($options['notes_class'] !== null) {
            $noteClass = ' class="'.$options['notes_class'].'"';
        }
        
        // Construct notes
        $notes .= '<ol'.$noteClass.'>';
        foreach ($results[0] as $key => $result) {
            // Build anchors
            $anchorNote = $notesAnchor.'-'.$key;
            $anchorRef = $notesAnchor.'-ref-'.$key;
            
            // Build reference
            $reference = (!empty($results[2][$key]) ? $results[3][$key] : '').
                         '<a href="#'.$anchorNote.'" id="'.$anchorRef.'">'.
                         '<sup>'.($key+1).'</sup>'.
                         '</a>';
            
            // Insert reference
            $text = substr($text, 0, strpos($text, $result)).
                    $reference.
                    substr($text, strpos($text, $result)+strlen($result));
            
            // Get note
            if (empty($results[2][$key])) {
                $note =  $results[3][$key];
            } else {
                $note =  $results[2][$key];
            }
            
            // Build note
            $notes .= '<li id="'.$anchorNote.'">'.
                      '<a href="#'.$anchorRef.'">&uarr;</a> '.$note.
                      '</li>';
        }
        $notes .= '</ol>';
        
        // Insert notes
        if (strpos($text, '[notes]') !== false) {
            $text = str_replace('[notes]', $notes, $text);
        } else {
            $text .= "\n".$notes; 
        }
    }
    
    // Flash objects
    $text = preg_replace(
        '/\\[embed-flash="?([0-9]+),([0-9]+)"?\]/',
        '<embed type="application/x-shockwave-flash" width="$1" height="$2" '.
        'allowFullScreen="true" allowScriptAccess="always" src="',
        $text
    );
    $text = str_replace('[/embed-flash]', '"></embed>', $text);
    
    // Iframes
    $text = preg_replace(
        '/\\[iframe="?([0-9]+),([0-9]+)"?\]/',
        '<iframe width="$1" height="$2" frameborder="0" src="',
        $text
    );
    $text = str_replace(
        '[/iframe]',
        '"></iframe>',
        $text
    );
    
    // Remove some new lines
    $tags = array('li','[uo]l','h[0-9]+','p','div','hr','pre');
    foreach ($tags as $tag) {
        $text = preg_replace(
            '/\s*(<\/?'.$tag.'( [^>]+)?\/?>)\s*/',
            '$1',
            $text
        );
    }
    
    // Replace new lines by brs
    $text = str_replace("\n", "\n".'<br/>', trim($text));
    
    // Remove brs for codes
    $codePattern = '/\\<code(.*?)\\>(.*?)\\<\\/code\\>/s';
    if (preg_match_all($codePattern, $text, $results)) {
        foreach ($results[0] as $code) {
            $text = str_replace($code, str_replace('<br/>', '', $code), $text);
        }
    }
    
    
    // Surround by a div
    $class = '';
    if (!empty($options['class'])) {
        $class = ' class="'.$options['class'].'"';
    }
    $text = '<div'.$class.'><a name="'.$topAnchor.'"></a>'.$text.'</div>';
    
    // Return text
    return $text;
}