<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quản lý Lịch Khám</h3>
        <p class="mt-2"><a href="{ADD_LINK}" class="btn btn-sm btn-success">Thêm Lịch Khám Mới</a></p>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Bệnh Nhân</th>
                        <th>Bác Sĩ</th>
                        <th>Ngày Khám</th>
                        <th>Giờ Khám</th>
                        <th>Trạng Thái</th>
                        <th style="width: 200px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{ROW.id}</td>
                        <td>{ROW.ten_benhnhan}</td>
                        <td>{ROW.ten_bacsi}</td>
                        <td>{ROW.ngaykham}</td>
                        <td>{ROW.giokham}</td>
                        <td>
                            <span class="badge badge-secondary">{ROW.trangthai_text}</span>
                        </td>
                        <td>
                            <a href="{ROW.link_detail}" class="btn btn-sm btn-info" title="Chi tiết"><em class="fa fa-eye"></em></a>
                            <a href="{ROW.link_edit}" class="btn btn-sm btn-primary" title="Sửa"><em class="fa fa-edit"></em></a>
                            <a href="{ROW.link_delete}" class="btn btn-sm btn-danger confirm_delete" title="Xóa"><em class="fa fa-trash"></em></a>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted mt-2">{TOTAL_INFO}</p>
            </div>
            <div class="col-md-6 text-right">
                {NV_GENERATE_PAGE}
                </div>
        </div>
    </div>
    <div class="card-body">
        <h4 class="card-title mb-3">Chi Tiết Lịch Khám ID: {ROW.id}</h4>
        
        <p><a href="{BACK_LINK}" class="btn btn-sm btn-secondary">← Quay lại Danh sách</a></p>

        <table class="table table-bordered detail-table">
            <tr><td><strong>Bệnh nhân</strong></td><td>{ROW.ten_benhnhan}</td></tr>
            <tr><td><strong>Bác sĩ phụ trách</strong></td><td>{ROW.ten_bacsi}</td></tr>
            <tr><td><strong>Thời gian</strong></td><td>{ROW.giokham} ngày {ROW.ngaykham_vn}</td></tr>
            <tr><td><strong>Trạng thái</strong></td><td><span class="badge badge-primary">{ROW.trangthai_text}</span></td></tr>
            <tr><td><strong>Ghi chú</strong></td><td>{ROW.ghichu}</td></tr>
        </table>

        <div class="action-buttons mt-4">
            <a href="{LINK_CONFIRM}" class="btn btn-success">Xác nhận Lịch khám</a>
            <a href="{LINK_CANCEL}" class="btn btn-warning">Hủy Lịch khám</a>
            <a href="{LINK_EDIT}" class="btn btn-primary">Chỉnh sửa</a>
            <a href="{LINK_DELETE}" class="btn btn-danger confirm_delete">Xóa Lịch khám</a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">{MESSAGE}</div>
    </div>
    </div>