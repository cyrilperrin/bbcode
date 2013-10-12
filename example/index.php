<?php

// Require
require('../src/bbcode.php');

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>BBCode</title>
        <style type="text/css">
            #page {
                width:1000px;
            }
            .bbcode {
                background-color:#CCCCCC;
                border:1px solid #AAAAAA;
                font-size:1px;
            }
            .bbcode div {
                display:inline;
                margin-right:8px;
            }
            .bbcode div a {
                display:inline-block;
                padding:2px;
            }
            .bbcode div a:hover {
                background-color:#AAAAAA;
            }
            img {
                border:0px;
            }
            #textarea {
                border:1px solid #AAAAAA;
                width:100%;
                height:250px;
            }
            input[type="submit"] {
                background-color:#CCCCCC;
                border:1px solid #AAAAAA;
            }
            input[type="submit"]:hover {
                background-color:#AAAAAA;
            }
            #result {
                background-color:#CCCCCC;
                padding:10px;
            }
            p.information, p.warning, p.error {
                background-repeat:no-repeat;
                background-position:5px 5px;
                padding-left:40px;
                padding-top:10px;
                min-height:30px;
            }
            p.information {
                background-color:#8AB6E0;
                background-image:url('farm/32x32/information.png');
            }
            p.warning {
                background-color:#FBDD73;
                background-image:url('farm/32x32/error.png');
            }
            p.error {
                background-color:#FA998A;
                background-image:url('farm/32x32/exclamation.png');
            }
        </style>
        <script type="text/javascript">
        /**
         * Insert BBCode into input
         * @param input node input node
         * @param bbopen string open tag
         * @param bbclose string close tag
         */
        function bbcode(input,bbopen,bbclose) {
            // Be sure bbclose is set
            if(bbclose == null) {
                bbclose = '';
            }
            
            // Focus on input
            input.focus();
            
            if (typeof document.selection != 'undefined') {
                // Insert BBCode
                var range = document.selection.createRange();
                var text = range.text;
                range.text = bbopen + text + bbclose;
                
                // Create new range
                range = document.selection.createRange();
                if (text.length == 0) {
                    range.move('character', -bbclose.length);
                } else {
                    range.moveStart(
                        'character',
                        bbopen.length+text.length+bbclose.length
                    );
                }
                range.select();
            } else if (typeof input.selectionStart != 'undefined') {
                // Insert BBCode
                var start = input.selectionStart;
                var end = input.selectionEnd;
                var text = input.value.substring(start, end);
                input.value = input.value.substr(0, start) +
                              bbopen +
                              text +
                              bbclose +
                              input.value.substr(end);
                
                // Create new range
                input.selectionStart = start + bbopen.length;
                input.selectionEnd = end + bbopen.length;
                input.focus();
            }
        }
        </script>
    </head>
    <body>
        <div id="page">
            <form action="#" method="post">
                <div class="bbcode">
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[center]','[/center]');return false;" title="Center"><img src="farm/16x16/text_align_center.png" alt="Center" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[left]','[/left]');return false;" title="Left"><img src="farm/16x16/text_align_left.png" alt="Left" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[right]','[/right]');return false;" title="Right"><img src="farm/16x16/text_align_right.png" alt="Right" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[justify]','[/justify]');return false;" title="Justify"><img src="farm/16x16/text_align_justity.png" alt="Justify" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[b]','[/b]');return false;" title="Bold"><img src="farm/16x16/text_bold.png" alt="Bold" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[u]','[/u]');return false;" title="Underline"><img src="farm/16x16/text_underline.png" alt="Underline" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[i]','[/i]');return false;" title="Italic"><img src="farm/16x16/text_italic.png" alt="Italic" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[s]','[/s]');return false;" title="Strikethrough"><img src="farm/16x16/text_strikethroungh.png" alt="Strikethrough" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[sub]','[/sub]');return false;" title="Subscript"><img src="farm/16x16/text_subscript.png" alt="Subscript" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[sup]','[/sup]');return false;" title="Superscript"><img src="farm/16x16/text_superscript.png" alt="Superscript" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[color=]','[/color]');return false;" title="Color"><img src="farm/16x16/color_picker.png" alt="Color" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[h1]','[/h1]');return false;" title="Header 1"><img src="farm/16x16/text_heading_1.png" alt="Header 1" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[h2]','[/h2]');return false;" title="Header 2"><img src="farm/16x16/text_heading_2.png" alt="Header 2" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[h3]','[/h3]');return false;" title="Header 3"><img src="farm/16x16/text_heading_3.png" alt="Header 3" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[h4]','[/h4]');return false;" title="Header 4"><img src="farm/16x16/text_heading_4.png" alt="Header 4" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[list][*]','[/list]');return false;" title="Bulleted list"><img src="farm/16x16/text_list_bullets.png" alt="Bulleted list" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[list=1][*]','[/list]');return false;" title="Numbered list"><img src="farm/16x16/text_list_numbers.png" alt="Numbered list" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[indent]','[/indent]');return false;" title="Indent"><img src="farm/16x16/text_indent.png" alt="Indent" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[img]','[/img]');return false;" title="Image"><img src="farm/16x16/picture.png" alt="Image" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[url]','[/url]');return false;" title="Link"><img src="farm/16x16/world.png" alt="Link" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[email]','[/email]');return false;" title="Email"><img src="farm/16x16/email.png" alt="Email" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[quote]','[/quote]');return false;" title="Quote"><img src="farm/16x16/comment.png" alt="Quote" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[spoiler]','[/spoiler]');return false;" title="Spoiler"><img src="farm/16x16/application_error.png" alt="Spoiler" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[code]','[/code]');return false;" title="Code"><img src="farm/16x16/page_code.png" alt="Code" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[imgs-left]','[/imgs-left]');return false;" title="Align images to left"><img src="farm/16x16/align_left.png" alt="Align images to left" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[imgs-right]','[/imgs-right]');return false;" title="Align images to right"><img src="farm/16x16/align_right.png" alt="Align images to right" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[index]');return false;" title="Index"><img src="farm/16x16/document_index.png" alt="Index" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[tooltip=]','[/tooltip]');return false;" title="Tooltip"><img src="farm/16x16/document_quote.png" alt="Tooltip" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[note]','[/note]');return false;" title="Note"><img src="farm/16x16/document_move.png" alt="Note" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[notes]');return false;" title="Notes"><img src="farm/16x16/directory_listing.png" alt="Notes" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[embed-flash=480,405]','[/embed-flash]');return false;" title="Flash"><img src="farm/16x16/page_white_flash.png" alt="Flash" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[iframe=480,405]','[/iframe]');return false;" title="Iframe"><img src="farm/16x16/application.png" alt="Iframe" /></a>
                    </div>
                    <div>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[information]','[/information]');return false;" title="Information"><img src="farm/16x16/information.png" alt="Information" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[warning]','[/warning]');return false;" title="Warning"><img src="farm/16x16/error.png" alt="Warning" /></a>
                        <a href="#" onclick="bbcode(document.getElementById('textarea'),'[error]','[/error]');return false;" title="Error"><img src="farm/16x16/exclamation.png" alt="Error" /></a>
                    </div>
                </div>        
                <textarea id="textarea" name="bbcode"><?php
                    if (isset($_POST['bbcode'])) {
                    echo htmlspecialchars($_POST['bbcode']);
                    } else {
                    echo '[h1]Index[/h1]',"\n",
                         '[index]',"\n\n",
                         '[h1]Text[/h1]',"\n",
                         'You can add [b]bold text[/b], [u]underlined text[/u], [s]strikethrough text[/s] and [color=red]colored text[/color].',"\n\n",
                         'You can also add [sub]subscript[/sub] and [sup]superscript[/sup].',"\n\n",
                         'You can also add a tooltip on [tooltip="tooltip"]text[/tooltip] or just a [tooltip]tooltip[/tooltip] alone.',"\n\n",
                         'You can also add a note on [note="note"]text[/note] or just a [note]note[/note] alone.',"\n\n",
                         '[h1]Links[/h1]',"\n",
                         'You can add a name link ([url="http://www.example.org"]link[/url]) or not ([url]http://www.example.org[/url]).',"\n\n",
                         'You can also add a [email="username@example.org"]mailto link[/email].',"\n\n",
                         '[h1]Lists[/h1]',"\n",
                         'You can add a bulleted list :',"\n",
                         '[list]',"\n",
                         '[*] Item',"\n",
                         '[*] Item',"\n",
                         '[*] Item',"\n",
                         '[/list]',"\n\n",
                         'You can also add a numbered list by numbers :',"\n",
                         '[list=1]',"\n",
                         '[*] Item',"\n",
                         '[*] Item',"\n",
                         '[*] Item',"\n",
                         '[/list]',"\n\n",
                         'You can also add a numbered list by letters :',"\n",
                         '[list=a]',"\n",
                         '[*] Item',"\n",
                         '[*] Item',"\n",
                         '[*] Item',"\n",
                         '[/list]',"\n\n",
                         '[h1]Objects[/h1]',"\n\n",
                         '[h2]Image[/h2]',"\n",
                         'You can add an image :',"\n",
                         '[img]http://upload.wikimedia.org/wikipedia/commons/',
                         'thumb/3/35/Tux.svg/200px-Tux.svg.png[/img]',"\n\n",
                         '[h2]Quote[/h2]',"\n",
                         'You can add a quote :',"\n",
                         '[quote]Quote[/quote]',"\n\n",
                         'You can also add a quote with its author :',"\n",
                         '[quote="Author"]Quote[/quote]',"\n\n",
                         '[h2]Spoiler[/h2]',"\n",
                         'You add a spoiler :',"\n",
                         '[spoiler]Spoiler[/spoiler]',"\n\n",
                         'You add a spoiler with a description :',"\n",
                         '[spoiler="Description"]Spoiler[/spoiler]',"\n\n",
                         '[h2]Code[/h2]',"\n",
                         'You can add code :',"\n",
                         '[code]Code[/code]',"\n\n",
                         '[h2]Notes[/h2]',"\n",
                         'You can define where notes appear :',"\n",
                         '[notes]',"\n",
                         'Or let them appear at the end of the page.',"\n\n",
                         '[h2]Iframe[/h2]',"\n",
                         'You can add an iframe :',"\n",
                         '[iframe=560,315]//www.youtube.com/embed/',
                         'Pwe-pA6TaZk?rel=0[/iframe]',"\n\n",
                         '[h1]Messages[/h1]',"\n",
                         'You can add differents types of messages :',"\n",
                         '[information]Information[/information]',"\n",
                         '[warning]Warning[/warning]',"\n",
                         '[error]Error[/error]';
                    }
                ?></textarea>
                <input type="submit" value="Submit" />
            </form>
            <?php
            // BBCode
            if (isset($_POST['bbcode'])) {
                echo '<h1>Result</h1><div id="result">',
                     bbcode($_POST['bbcode'], array('headers_max_level' => 1)),
                     '</div>';
            }
            ?>
        </div>
    </body>
</html>