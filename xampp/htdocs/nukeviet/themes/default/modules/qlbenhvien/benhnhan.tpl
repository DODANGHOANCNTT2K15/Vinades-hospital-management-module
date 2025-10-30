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

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Giới Tính</label><br>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="gioitinh_nam" name="gioitinh" value="nam" class="custom-control-input" {GIOITINH_NAM_CHECKED}>
                            <label class="custom-control-label" for="gioitinh_nam">Nam</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="gioitinh_nu" name="gioitinh" value="nu" class="custom-control-input" {GIOITINH_NU_CHECKED}>
                            <label class="custom-control-label" for="gioitinh_nu">Nữ</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ngày Sinh</label>
                        <input type="date" name="ngaysinh" value="{DATA.ngaysinh}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Điện Thoại <span class="text-danger">(*)</span></label>
                <input type="text" name="dienthoai" value="{DATA.dienthoai}" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Địa Chỉ</label>
                <input type="text" name="diachi" value="{DATA.diachi}" class="form-control">
            </div>

            <div class="mt-4">
                <input type="submit" name="submit" value="{LANG.save_button}" class="btn btn-primary">
                <a href="{NV_BASE_ADMINURL}index.php?nv={MODULE_NAME}&op=benhnhan" class="btn btn-secondary">{LANG.back}</a>
            </div>
            
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{TOTAL_INFO}</h3>
        <div class="card-tools">
            <a href="{ADD_LINK}" class="btn btn-primary btn-sm"><em class="fa fa-plus"></em> Thêm Bệnh Nhân</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">STT</th>
                        <th>Họ Tên</th>
                        <th class="text-center" style="width: 100px;">Giới Tính</th>
                        <th class="text-center" style="width: 120px;">Ngày Sinh</th>
                        <th style="width: 120px;">Điện Thoại</th>
                        <th>Địa Chỉ</th>
                        <th class="text-center" style="width: 150px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{ROW.stt}</td>
                        <td>{ROW.hoten}</td>
                        <td class="text-center">{ROW.gioitinh_text}</td>
                        <td class="text-center">{ROW.ngaysinh_vn}</td>
                        <td>{ROW.dienthoai}</td>
                        <td>{ROW.diachi}</td>
                        <td class="text-center">
                            <a href="{ROW.link_edit}" class="btn btn-info btn-sm"><em class="fa fa-edit"></em></a>
                            <a href="{ROW.link_delete}" class="btn btn-danger btn-sm" data-confirm="Bạn có chắc chắn muốn xóa bệnh nhân này không?"><em class="fa fa-trash"></em></a>
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