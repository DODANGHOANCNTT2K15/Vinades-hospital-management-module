<!-- BEGIN: main -->
<script src="{NV_STATIC_URL}themes/default/js/jquery.matchHeight-min.js" type="text/javascript"></script>
<div class="panel panel-primary">
    <div class="panel-heading">
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
        <p class="short-desc">{DATA.description}</p>
        <!-- END: about -->

        <p class="text-center">
            {admin_link}
        </p>

        <!-- BEGIN: person -->
        <hr />
        <div class="row">
            <!-- BEGIN: loop -->
            <div class="col-sm-6 col-md-6">
                <div class="thumbnail">
                    <div style="height: {HEIGHT}px">
                        <a href="{ROW.link}" title="{ROW.name}"><img class="imgthumbnail" src="{ROW.photo}" style="max-height: {HEIGHT}px" alt="{ROW.name}"></a>
                    </div>
                    <div class="caption text-center">
                        <h3><a href="{ROW.link}" title="{ROW.name}">{ROW.name}</a></h3>
                        <p>
                            {ROW.position}<br /> {ROW.birthday}
                        </p>
                    </div>
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: person -->
    </div>
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
    $(function() {
        $('.thumbnail').matchHeight({
            property: 'min-height'
        });
    });
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