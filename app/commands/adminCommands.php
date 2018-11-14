<?php enforceAdminOnly($role); ?>
<div class="bodyWrap">
    <div class="container col-sm-7">
        <div>
            <div class="paragraphMarginTop">
                <a href="?command=manageAccounts">
                    <button type="button" class="btn btn-info btn-block">Accounts beheren</button>
            </div>
            <div class="paragraphMarginSmallTop">
                <a href="?command=manageClasses">
                    <button type="button" class="btn btn-info btn-block">Klassen beheren</button>
                </a>
            </div>
            <div class="paragraphMarginSmallTop">
                <a href="?command=addStudent">
                    <button type="button" class="btn btn-info btn-block">Student toevoegen</button>
                </a>
            </div>
            <div class="paragraphMarginSmallTop">
                <a href="?command=addTeacher">
                    <button type="button" class="btn btn-info btn-block">Docent toevoegen</button>
                </a>
            </div>
        </div>
    </div>
</div>