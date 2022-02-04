</div>
<script src="<?php echo ROUTE_JS; ?>jquery-3.6.0.min.js"></script>
<script src="<?php echo ROUTE_JS; ?>popper.min.js"></script>
<script src="<?php echo ROUTE_JS; ?>bootstrap.min.js"></script>
<script src="<?php echo ROUTE_JS; ?>slick.min.js"></script>
<script src="<?php echo ROUTE_JS; ?>alertify.min.js"></script>
<?php 
if (isset($global_js_scripts)) {
    foreach ($global_js_scripts as $script) {
        echo $script;
    }
}
?>
<?php $files_routes = isset($files_js_routes) ? $files_js_routes : []; foreach ($files_routes as $file) { echo "<script src=".$file."></script>"; } ?>
</body>

</html>