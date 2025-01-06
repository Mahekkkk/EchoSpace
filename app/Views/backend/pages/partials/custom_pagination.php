<?php 
$page = $page ?? 1; 
$perPage = $perPage ?? 10; 
$total = $total ?? 0; 
$posts = $posts ?? []; 

$pager->setSurroundCount(2); 
?>

<div class="row">
    <!-- Pagination -->
    <div class="col-12">
        <ul class="pagination justify-content-center">
            <?php if ($pager->hasPrevious()): ?>
                <li class="page-item">
                    <a href="<?= $pager->getFirst() ?>" class="page-link">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                        &laquo;
                    </a>
                </li>
            <?php endif; ?>

            <?php foreach ($pager->links() as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                </li>
            <?php endforeach; ?>

            <?php if ($pager->hasNext()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
                        &raquo;
                    </a>
                </li>
                <li class="page-item">
                    <a href="<?= $pager->getLast() ?>" class="page-link">Last</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- Pagination Details -->
    <div class="col-12 text-center">
        <p class="text-muted">
            Showing <?= (($page - 1) * $perPage + 1) ?>-<?= (($page + 1) * $perPage + count($posts)) ?> 
            of <?= number_format($total) ?> results
        </p>
    </div>
</div>
