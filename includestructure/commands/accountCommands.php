<div class="bodyWrap">
    <div class="container">
        <div>
            <div class="paragraphMarginTop">
                <a href="?command=accounts"><button type="button" class="btn btn-info btn-block">Alle accounts</button>
            </div>
            <div class="paragraphMarginSmallTop">
                <a href=""><button type="button" class="btn btn-info btn-block">Alle klassen</button></a>
            </div>
            <?php if ($role->isTeacher() || $role->isAdmin()) {?>
                <div class="paragraphMarginSmallTop">
                    <a href="?command=addStudent"><button type="button" class="btn btn-info btn-block">Student toevoegen</button></a>
                </div>
            <?php } ?>
            <?php if ($role->isAdmin()) { ?>
                <div class="paragraphMarginSmallTop">
                    <a href="?command=addTeacher"><button type="button" class="btn btn-info btn-block">Docent toevoegen</button></a>
                </div>
            <?php } ?>
            <div class="paragraphMarginSmallTop">
                <a href=""><button type="button" class="btn btn-info btn-block">Klas toevoegen</button></a>
            </div>
        </div>
    </div>
</div>