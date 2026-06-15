<?php
require_once __DIR__ . '/../templates/header.php';

$db->query("SELECT sg.*, m.tournament_name, t1.name_en as home, t2.name_en as away, b.name as b_name, b.logo as b_logo, o.name as o_name, o.logo as o_logo, o.url as o_url
            FROM streaming_guides sg
            JOIN matches m ON sg.match_id = m.id
            JOIN teams t1 ON m.team_home_id = t1.id
            JOIN teams t2 ON m.team_away_id = t2.id
            LEFT JOIN broadcasters b ON sg.broadcaster_id = b.id
            LEFT JOIN ott_platforms o ON sg.ott_id = o.id
            WHERE m.status IN ('live', 'upcoming')
            ORDER BY m.match_date ASC");
$guides = $db->resultSet();

$page_title = $texts['where_to_watch'];
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold"><?php echo $texts['where_to_watch']; ?> ?> ?></h2>
        <p class="text-muted">Official broadcasters and streaming platforms for upcoming matches.</p>
    </div>

    <div class="row">
        <?php if (empty($guides)): ?>
            <div class="col-12 text-center py-5">
                <p>No streaming guides available at the moment.</p>
            </div>
        <?php else: ?>
            <?php foreach($guides as $g): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-warning text-dark"><?php echo e($g['tournament_name']); ?> ?></span>
                            <span class="fw-bold"><i class="fas fa-globe me-1"></i> <?php echo e($g['country']); ?> ?></span>
                        </div>
                        <h5 class="mb-4"><?php echo e($g['home']); ?> ?> vs <?php echo e($g['away']); ?> ?></h5>

                        <div class="row text-center">
                            <?php if($g['b_name']): ?>
                            <div class="col-6 border-end">
                                <small class="text-muted d-block mb-2">Broadcaster</small>
                                <img src="../uploads/<?php echo $g['b_logo'] ?: 'default_b.png'; ?>" height="40" class="mb-2">
                                <div class="fw-bold"><?php echo e($g['b_name']); ?> ?></div>
                            </div>
                            <?php endif; ?>

                            <?php if($g['o_name']): ?>
                            <div class="col-6">
                                <small class="text-muted d-block mb-2">OTT Platform</small>
                                <img src="../uploads/<?php echo $g['o_logo'] ?: 'default_o.png'; ?>" height="40" class="mb-2">
                                <div class="fw-bold"><a href="<?php echo e($g['o_url']); ?> ?>" target="_blank" class="text-decoration-none text-dark"><?php echo e($g['o_name']); ?> ?></a></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
