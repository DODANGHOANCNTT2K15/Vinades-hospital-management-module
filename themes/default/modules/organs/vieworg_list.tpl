<!-- BEGIN: main -->
<div class="panel panel-primary table_org">
    <div class="vieworg">
        <div class="org">
            {DATA.title}
        </div>
        <div class="panel-body">
            <ul style="padding: 0">
                <!-- BEGIN: address -->
                <li>
                    <strong>{LANG.vieworg_address}:</strong> {DATA.address}
                </li>
                <!-- END: address -->
                <!-- BEGIN: phone -->
                <li>
                    <strong>{LANG.vieworg_phone}:</strong> {DATA.phone}
                </li>
                <!-- END: phone -->
                <!-- BEGIN: fax -->
                <li>
                    <strong>{LANG.vieworg_fax}:</strong> {DATA.fax}
                </li>
                <!-- END: fax -->
                <!-- BEGIN: website -->
                <li>
                    <strong>{LANG.vieworg_website}:</strong> {DATA.website}
                </li>
                <!-- END: website -->
            </ul>

            <!-- BEGIN: about -->
            <p class="short-desc">
                {DATA.description}
            </p>
            <!-- END: about -->

            <p class="text-center">
                {admin_link}
            </p>
        </div>
    </div>
    <!-- BEGIN: person -->

    <!-- BEGIN: loop -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col width="170" />
                <col />
                <col width="110" />
            </colgroup>
            <tbody>
                <tr>
                    <td>{ROW.position}</td>
                    <td><strong><a href="{ROW.link}" title="{ROW.name}" >{ROW.name}</a></strong></td>
                    <td rowspan="5" style="text-align: center; vertical-align: middle"><a href="{ROW.link}" title="{ROW.name}"><img src="{ROW.photo}" class="img-thumbnail" style="max-width: {WIDTH}px" alt="{ROW.name}"></a></td>
                </tr>
                <tr>
                    <td>{LANG.vieworg_professional}</td>
                    <td>{ROW.professional}</td>
                </tr>
                <tr>
                    <td>{LANG.vieworg_phone}</td>
                    <td>{ROW.phone}</td>
                </tr>
                <tr>
                    <td>{LANG.vieworg_email}</td>
                    <td><a href="mailto:{ROW.email}">{ROW.email}</a></td>
                </tr>
                <tr>
                    <td>{LANG.viewper_address}</td>
                    <td>{ROW.address}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr />
    <!-- END: loop -->
    <!-- END: person -->
</div>

<!-- BEGIN: pages -->
<div class="text-center">
    {html_pages}
</div>
<!-- END: pages -->
<style>
    .short-desc .morecontent span {
        display: none;
    }
    .morelink, .morelink:link {
        display: block;
        color: #428bca;
        font-weight: bold;
        margin-top: 5px;
    }
    .morelink.less {
        margin-top: -25px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var showChar = 500;
        var ellipsestext = "...";
        var moretext = "{LANG.moretext}";
        var lesstext = "{LANG.lesstext}";
        var content = $('.short-desc').html();
        if ($('.short-desc').length) {
            if(content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext+ '</span><span class="morecontent"><span>' + h + '</span><a href="#" class="morelink">' + moretext + '</a></span>';

                $('.short-desc').html(html);
            }
        }
        $(".morelink").click(function(){
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
</script>
<!-- END: main -->