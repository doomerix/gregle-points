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
        <br>
        <form class="form justify-content-center" method="post" enctype="multipart/form-data" action="?command=manageAccounts">
            <div class="form-group mx-mb-2 justify-content-center">
                <label for="exampleInputFile"><b>Studenten Importeren</b></label>
                <input type="file" name="file" class="form-control-file text-center" id="exampleInputFile">
            </div>
            
            <button type="submit" class="btn btn-info mb-2">Importeren</button>
        </form>
        <span>Lever alleen .csv bestanden aan.</span>
    </div>

    
</div>