<?php
if ($ok==true){

if($slanguage==0)$lang='c_cpp';
else if($slanguage==1)$lang='c_cpp';
else if($slanguage==2)$lang='pascal';
else if($slanguage==3)$lang='java';
else if($slanguage==6)$lang='python';
echo '<pre id="editor">';
echo htmlentities(str_replace("\n\r","\n",$view_source),ENT_QUOTES,"utf-8")."\n".$auth."</pre>";
echo '<script src="/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/tomorrow");
    editor.session.setMode("ace/mode/'.$lang.'");
    editor.setReadOnly(true);
</script>
';

}else{
echo "I am sorry, You could not view this code!";
}
?>