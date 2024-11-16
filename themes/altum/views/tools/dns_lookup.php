<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('tools.dns_lookup.name') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.dns_lookup.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.dns_lookup.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="host"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('tools.dns_lookup.host') ?></label>
                    <input type="text" id="host" name="host" class="form-control <?= \Altum\Alerts::has_field_errors('host') ? 'is-invalid' : null ?>" value="<?= $data->values['host'] ?>" placeholder="<?= l('global.host_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('host') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">

            <?php if(isset($data->result['A'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('a')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-a-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('global.ip') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['A'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ip'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['AAAA'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('aaaa')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-aaaa-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.target') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['AAAA'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ipv6'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['CNAME'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('cname')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-cname-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.target') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['CNAME'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['target'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['MX'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('mx')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-mx-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.priority') ?></th>
                                <th><?= l('tools.dns_lookup.result.target') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['MX'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['pri'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['target'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['NS'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('ns')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-ns-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.ns') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['NS'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['target'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['TXT'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('txt')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-txt-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.entries') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['TXT'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['txt'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['SOA'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('soa')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-soa-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.mname') ?></th>
                                <th><?= l('tools.dns_lookup.result.rname') ?></th>
                                <th><?= l('tools.dns_lookup.result.serial') ?></th>
                                <th><?= l('tools.dns_lookup.result.refresh') ?></th>
                                <th><?= l('tools.dns_lookup.result.retry') ?></th>
                                <th><?= l('tools.dns_lookup.result.expire') ?></th>
                                <th><?= l('tools.dns_lookup.result.minimum_ttl') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['SOA'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['mname'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['rname'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['serial'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['refresh'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['retry'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['expire'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['minimum-ttl'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(isset($data->result['CAA'])): ?>
                <div class="my-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h5 m-0"><?= sprintf(l('tools.dns_lookup.result.x_records'), strtoupper('caa')) ?></h2>

                        <div class="ml-2">
                            <a href="https://www.cloudflare.com/learning/dns/dns-records/dns-caa-record/" target="_blank">
                                <i class="fas fa-fw fa-info-circle text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('global.type') ?></th>
                                <th><?= l('tools.dns_lookup.result.host') ?></th>
                                <th><?= l('tools.dns_lookup.result.ttl') ?></th>
                                <th><?= l('tools.dns_lookup.result.flags') ?></th>
                                <th><?= l('tools.dns_lookup.result.tag') ?></th>
                                <th><?= l('tools.dns_lookup.result.value') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data->result['CAA'] as $record): ?>

                                <tr>
                                    <td class="text-nowrap">
                                        <?= $record['type'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['host'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['ttl'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['flags'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['tag'] ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= $record['value'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

        </div>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

