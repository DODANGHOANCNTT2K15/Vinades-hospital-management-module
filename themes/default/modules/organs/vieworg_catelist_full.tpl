<!-- BEGIN: main -->
<!-- BEGIN: suborg -->
<strong>{LANG.list_sub_org}:</strong><br />
<div class="suborg"> <!--Cac phong ban truc thuoc-->
    
    <ol>
    <!-- BEGIN: loop  -->
    <li><h3><a href="{suborg.link}">{suborg.title}</a></h3></li>
    <!-- END: loop -->
    </ol>
</div>
<!-- END: suborg -->

<!-- BEGIN: cateloop -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>
            <h2><a href="{CATE.link}">{CATE.title}</a></h2>
            <!-- BEGIN: suborgan -->
            <!-- Single button -->
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <!-- BEGIN: loop -->
                    <li>
                        <a href="{SUBORGAN.link}">{SUBORGAN.title}</a>
                    </li>
                    <!-- END: loop -->
                </ul>
            </div>
            <!-- END: suborgan -->
            <!-- BEGIN: email --></br><strong>{LANG.email}:</strong>{CATE.email}<!-- END: email -->
            <!-- BEGIN: phone --></br><strong>{LANG.phone}:</strong>{CATE.phone}<!-- END: phone -->
            <!-- BEGIN: fax --></br><strong>{LANG.vieworg_fax}:</strong>{CATE.fax}<!-- END: fax -->
        </caption>
        <thead>
            <tr>
                <th class="text-center">{LANG.vieworg_no}</th>
                <th class="text-center">{LANG.vieworg_name}</th>
                <th class="text-center">{LANG.vieworg_position}</th>
                <th class="text-center">{LANG.photo}</th>
            </tr>
        </thead>
        <tbody>
            
            <!-- BEGIN: loop  -->
                <!-- BEGIN: cat  -->
                <tr>
                    <td colspan="4">
                        <h3><a href="{CAT.link}">{CAT.title}</a></h3>
                        <!-- BEGIN: email --></br><strong>{LANG.email}:</strong>{CAT.email}<!-- END: email -->
                    </td>
                </tr>
                <!-- END: cat -->
            <tr>
                <td class="text-center" style="text-align: center; vertical-align: middle">{ROW.no}</td>
                <td style="vertical-align: middle"><h3><a href="{ROW.link}"><strong>{ROW.name}</strong></a></h3>
                <!-- BEGIN: phone --><strong>{LANG.phone}:</strong><!-- END: phone -->{ROW.mobile}<!-- BEGIN: br1 --></br><!-- END: br1 --> {ROW.phone_ext}<!-- BEGIN: br2 --></br><!-- END: br2 --> {ROW.phone} <!-- BEGIN: email --></br><strong>{LANG.email}:</strong> {ROW.email}</br><!-- END: email --></td>
                <td class="text-center position" style="text-align: center; vertical-align: middle"><strong> {ROW.position}
                {ROW.position_other}
                {ROW.professional} </strong></td>
                <td style="text-align: center; vertical-align: middle"><a href="{ROW.link}" title="{ROW.name}"><img src="{ROW.photo}" class="img-thumbnail" style="max-width: {WIDTH}px" alt="{ROW.name}"></a></td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: cateloop -->

<!-- END: main -->