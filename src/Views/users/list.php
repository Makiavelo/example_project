<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Users list</div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php if ($users): ?>
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Last name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user->getId(); ?></td>
                                        <td><?php echo $user->getName(); ?></td>
                                        <td><?php echo $user->getLastName(); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h2>No users found</h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
