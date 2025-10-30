<div class="card">
    <div class="card-header">
        <h3 class="card-title">{TITLE}</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">{ERROR}</div>
        <form action="{FORM_ACTION}" method="post">
            
            <div class="form-group">
                <label>Họ Tên <span class="text-danger">(*)</span></label>
                <input type="text" name="hoten" value="{DATA.hoten}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Chuyên Khoa <span class="text-danger">(*)</span></label>
                <input type="text" name="chuyenkhoa" value="{DATA.chuyenkhoa}" class="form-control" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Điện Thoại <span class="text-danger">(*)</span></label>
                        <input type="text" name="dienthoai" value="{DATA.dienthoai}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{DATA.email}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <input type="submit" name="submit" value="{LANG.save_button}" class="btn btn-primary">
                <a href="{NV_BASE_ADMINURL}index.php?nv={MODULE_NAME}&op=bacsi" class="btn btn-secondary">{LANG.back}</a>
            </div>
            
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{TOTAL_INFO}</h3>
        <div class="card-tools">
            <a href="{ADD_LINK}" class="btn btn-primary btn-sm"><em class="fa fa-plus"></em> Thêm Bác Sĩ</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">STT</th>
                        <th>Họ Tên</th>
                        <th style="width: 200px;">Chuyên Khoa</th>
                        <th style="width: 150px;">Điện Thoại</th>
                        <th style="width: 200px;">Email</th>
                        <th class="text-center" style="width: 150px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{ROW.stt}</td>
                        <td>{ROW.hoten}</td>
                        <td>{ROW.chuyenkhoa}</td>
                        <td>{ROW.dienthoai}</td>
                        <td>{ROW.email}</td>
                        <td class="text-center">
                            <a href="{ROW.link_edit}" class="btn btn-info btn-sm"><em class="fa fa-edit"></em></a>
                            <a href="{ROW.link_delete}" class="btn btn-danger btn-sm" data-confirm="Bạn có chắc chắn muốn xóa bác sĩ này không?"><em class="fa fa-trash"></em></a>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {NV_GENERATE_PAGE}
    </div>
    </div>