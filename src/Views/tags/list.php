<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Tags list</div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php if ($tags): ?>
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tags as $tag): ?>
                                    <tr>
                                        <td><?php echo $tag->getId(); ?></td>
                                        <td><?php echo $tag->getName(); ?></td>
                                        <td>
                                            <a href="/admin/tags/edit/<?php echo $tag->getId(); ?>" class="btn btn-primary mt-3">Edit</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h2>No tags found</h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
