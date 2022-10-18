        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted"><?= $config["org_name"];?></span>
        </div>
    </footer>
</body>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/bootstrap-table.min.js"></script>
<script src="assets/js/bootstrap-table-ru-RU.min.js"></script>
<?php 
    if (isset($customjs)) {
        echo $customjs;
    }
?>
</html>