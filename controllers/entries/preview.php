<?php Block::put('breadcrumb') ?>
<ul>
    <li><a href="<?= Backend::url('sixgweb/forms/entries') ?>">Entries</a></li>
    <li><?= e($this->pageTitle) ?></li>
</ul>
<?php Block::endPut() ?>

<?php if (!$this->fatalError) : ?>

    <div class="control-toolbar" data-control=" toolbar">
        <a href="<?= Backend::url('sixgweb/forms/entries/update/' . $formModel->id) ?>" class="btn btn-primary oc-icon-pencil">
            Edit Entry
        </a>
    </div>

    <div class="form-preview">
        <?= $this->formRenderPreview() ?>
    </div>

<?php else : ?>

    <p class="flash-message static error"><?= e($this->fatalError) ?></p>
    <p><a href="<?= Backend::url('sixgweb/forms/entries') ?>" class="btn btn-default"><?= e(trans('backend::lang.form.return_to_list')) ?></a></p>

<?php endif ?>