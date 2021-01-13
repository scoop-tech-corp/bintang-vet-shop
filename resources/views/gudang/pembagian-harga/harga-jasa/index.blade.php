<div>
  <div class="inner-box-title">
    <button class="btn btn-info openFormAdd">Tambah</button>
    <div class="input-search-section">
      <input type="text" class="form-control" placeholder="cari..">
      <i class="fa fa-search" aria-hidden="true"></i>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped text-nowrap">
      <thead style="border-top: 1px solid #f4f4f4;">
        <tr>
          <th>No</th>
          <th class="onOrdering" data='service_name' orderby="none">Jenis Pelayanan <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='category_name' orderby="none">Kategori Jasa <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='branch_name' orderby="none">Cabang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_at' orderby="none">Tanggal dibuat <span class="fa fa-sort"></span></th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="list-harga-jasa"></tbody>
    </table>
  </div>

  @component('gudang.pembagian-harga.harga-jasa.modal-harga-jasa') @endcomponent
</div>