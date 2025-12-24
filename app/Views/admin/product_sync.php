<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">StreamingPH Product Sync</h3>
                <div>
                     <form action="<?= base_url('admin/products/sync') ?>" method="post" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync"></i> Sync Balances
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                
                <?php if(session()->getFlashdata('message')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-box"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Local Products</span>
                                <span class="info-box-number"><?= $status['local_total'] ?></span>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-cloud"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Remote Products</span>
                                <span class="info-box-number"><?= $status['remote_total'] ?></span>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-link"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Mapped</span>
                                <span class="info-box-number"><?= $status['mapped_count'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <h4>Product Mapping</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Local Product</th>
                                <th>Status</th>
                                <th>Mapped To (StreamingPH)</th>
                                <th>Balance / Validity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($status['local_products'] as $product): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($product['name']) ?></strong><br>
                                        <small class="text-muted">SKU: <?= esc($product['sku']) ?></small>
                                    </td>
                                    <td>
                                        <?php if(!empty($product['streamingph_product_id'])): ?>
                                            <span class="badge badge-success">Mapped</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Unmapped</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($product['streamingph_product_id'])): ?>
                                            <code><?= esc($product['streamingph_product_id']) ?></code>
                                        <?php else: ?>
                                            <select class="form-control form-control-sm map-select" data-local-id="<?= $product['id'] ?>">
                                                <option value="">-- Select Remote Product --</option>
                                                <?php foreach ($status['remote_products'] as $remote): ?>
                                                    <option value="<?= $remote['productID'] ?>">
                                                        <?= esc($remote['name']) ?> (<?= $remote['validity'] ?> days) - <?= $remote['productID'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($product['streamingph_product_id'])): ?>
                                            Balance: <?= esc($product['streamingph_balance']) ?><br>
                                            Validity: <?= esc($product['streamingph_validity']) ?? 'N/A' ?> days
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                         <?php if(!empty($product['streamingph_product_id'])): ?>
                                            <button class="btn btn-sm btn-danger btn-unmap" data-id="<?= $product['id'] ?>">Unmap</button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-primary btn-map" data-id="<?= $product['id'] ?>">Save Map</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Map Button
    document.querySelectorAll('.btn-map').forEach(btn => {
        btn.addEventListener('click', function() {
            const localId = this.dataset.id;
            const select = document.querySelector(`.map-select[data-local-id="${localId}"]`);
            const remoteId = select.value;

            if(!remoteId) {
                alert('Please select a remote product');
                return;
            }

            fetch('<?= base_url('admin/products/map') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: `local_id=${localId}&streamingph_id=${remoteId}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        });
    });

    // Unmap Button
    document.querySelectorAll('.btn-unmap').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm('Are you sure you want to unmap this product?')) return;

            const localId = this.dataset.id;

            fetch('<?= base_url('admin/products/unmap') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: `local_id=${localId}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        });
    });
});
</script>
<?= $this->endSection() ?>
