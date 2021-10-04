<?php
use Makiavelo\Flex\Util\Common;
?>
<div class="row">
    <div class="col-6">
        <div class="basic-form">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $user->getId(); ?>" />
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo $user->getName(); ?>" class="form-control input-default" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last name</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo $user->getLastName(); ?>" class="form-control input-default " placeholder="Last name">
                </div>
                <?php if ($tags): ?>
                    <div class="form-group">
                        <label>Tags</label>
                        <ul>
                        <?php foreach ($tags as $tag): ?>
                            <li>
                                <?php $checked = Common::find($user->getTags(), 'id', $tag->getId()); ?>
                                <input type="checkbox" name="tags[]" 
                                       value="<?php echo $tag->getId(); ?>"
                                    <?php if ($checked): ?>
                                       checked="checked"
                                    <?php endif; ?> />
                                <?php echo $tag->getName(); ?>
                            </li>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-6"></div>
</row>