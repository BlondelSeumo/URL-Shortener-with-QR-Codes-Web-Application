<?php defined('ALTUMCODE') || die() ?>

<?php $pages = $data->paginator->getPages(); ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center">
            <div class="text-center text-lg-left">
                <p class="text-muted mb-0">
                    <?= sprintf(l('global.pagination.results'), '<strong>' . $data->paginator->getCurrentPageFirstItem() . '</strong>', '<strong>' . $data->paginator->getCurrentPageLastItem() . '</strong>', '<strong>' . $data->paginator->getTotalItems() . '</strong>') ?>
                </p>
            </div>

            <?php if(count($pages)): ?>
                <ul class="pagination align-self-center align-self-lg-auto mb-0 mt-3 mt-lg-0">
                <?php if($data->paginator->getPrevUrl()): ?>
                    <li class="page-item"><a href="<?= $data->paginator->getPrevUrl(); ?>" class="page-link" aria-label="<?= l('global.pagination.previous') ?>">‹</a></li>
                <?php endif; ?>

                <?php foreach($data->paginator->getPages() as $page): ?>
                    <?php if($page['url']): ?>
                        <li class="page-item <?= $page['isCurrent'] ? 'active' : ''; ?>">
                            <a href="<?= $page['url']; ?>" class="page-link"><?= $page['num']; ?></a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link"><?= $page['num']; ?></span></li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if($data->paginator->getNextUrl()): ?>
                    <li class="page-item"><a href="<?= $data->paginator->getNextUrl(); ?>" class="page-link" aria-label="<?= l('global.pagination.next') ?>">›</a></li>
                <?php endif; ?>
            </ul>
            <?php endif ?>
        </div>
    </div>
</div>


