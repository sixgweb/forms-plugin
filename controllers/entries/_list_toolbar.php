<div data-control="toolbar">
    <a
        href="<?= Backend::url('sixgweb/forms/entries/create') ?>"
        class="btn btn-primary oc-icon-plus">
        <?= e(trans('backend::lang.list.create_button', ['name'=>'Entry'])) ?>
    </a>

    <button
        class="btn btn-danger oc-icon-trash-o"
        disabled="disabled"
        onclick="$(this).data('request-data', { checked: $('.control-list').listWidget('getChecked') })"
        data-request="onDelete"
        data-request-confirm="<?= e(trans('backend::lang.list.delete_selected_confirm')) ?>"
        data-trigger-action="enable"
        data-trigger=".control-list input[type=checkbox]"
        data-trigger-condition="checked"
        data-request-success="$(this).prop('disabled', 'disabled')"
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')) ?>
    </button>

    <a
        href="<?= Backend::url('sixgweb/forms/entries/export') ?>"
        class="btn btn-secondary oc-icon-download pull-right">
        Export
    </a>

    <a
        href="<?= Backend::url('sixgweb/forms/entries/import') ?>"
        class="btn btn-secondary oc-icon-upload pull-right">
        Import
    </a>


</div>
