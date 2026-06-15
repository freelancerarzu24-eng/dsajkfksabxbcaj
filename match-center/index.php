<?php
require_once __DIR__ . '/../templates/header.php';

$match_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($match_id > 0) {
    // Match Detail View
    $db->query("SELECT m.*, t1.name_en as home, t2.name_en as away, t1.logo as home_logo, t2.logo as away_logo
                FROM matches m
                JOIN teams t1 ON m.team_home_id = t1.id
                JOIN teams t2 ON m.team_away_id = t2.id
                WHERE m.id = :id");
    $db->bind(':id', $match_id);
    $match = $db->single();

    if (!$match) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Match not found.</div></div>";
    } else {
        ?>
        <div class="container mt-5">
            <div class="card shadow-lg mb-4">
                <div class="card-body text-center p-5">
                    <h5 class="text-muted mb-4"><?php echo e($match['tournament_name']); ?> ?> - <?php echo e($match['venue']); ?> ?></h5>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <img src="../uploads/<?php echo e($match['home_logo']); ?> ?>" width="100" class="mb-3">
                            <h3><?php echo e($match['home']); ?> ?></h3>
                        </div>
                        <div class="col-md-4">
                            <h1 class="display-1 fw-bold"><?php echo e($match['score_home']); ?> ?> : <?php echo e($match['score_away']); ?> ?></h1>
                            <span class="badge bg-<?php echo $match['status'] == 'live' ? 'danger' : 'secondary'; ?> fs-4"><?php echo strtoupper($match['status']); ?></span>
                        </div>
                        <div class="col-md-4">
                            <img src="../uploads/<?php echo e($match['away_logo']); ?> ?>" width="100" class="mb-3">
                            <h3><?php echo e($match['away']); ?> ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card p-4">
                        <h4>Match Details</h4>
                        <hr>
                        <p><?php echo e($match['match_details']); ?> ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 bg-light">
                        <h4>Where to Watch</h4>
                        <hr>
                        <?php
                        $db->query("SELECT sg.*, b.name as b_name, o.name as o_name, o.url as o_url
                                    FROM streaming_guides sg
                                    LEFT JOIN broadcasters b ON sg.broadcaster_id = b.id
                                    LEFT JOIN ott_platforms o ON sg.ott_id = o.id
                                    WHERE sg.match_id = :id");
                        $db->bind(':id', $match_id);
                        $guides = $db->resultSet();

                        if (empty($guides)) {
                            echo "<p>No streaming info available.</p>";
                        } else {
                            foreach($guides as $g) {
                                echo "<div class='mb-3'><strong>{$g['country']}:</strong><br>";
                                if($g['b_name']) echo "<span class='badge bg-dark me-2'>{$g['b_name']}</span>";
                                if($g['o_name']) echo "<a href='{$g['o_url']}' target='_blank' class='badge bg-primary'>{$g['o_name']}</a>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    // List All Matches
    $db->query("SELECT m.*, t1.name_en as home, t2.name_en as away, t1.logo as home_logo, t2.logo as away_logo
                FROM matches m
                JOIN teams t1 ON m.team_home_id = t1.id
                JOIN teams t2 ON m.team_away_id = t2.id
                ORDER BY m.match_date DESC");
    $all_matches = $db->resultSet();
    ?>
    <div class="container mt-5">
        <h2 class="fw-bold mb-4"><?php echo $texts['match_center']; ?> ?> ?></h2>
        <div class="row">
            <?php foreach($all_matches as $m): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between small text-muted mb-3">
                            <span><?php echo e($m['tournament_name']); ?> ?></span>
                            <span><?php echo date('M d, Y H:i', strtotime($m['match_date'])); ?></span>
                        </div>
                        <div class="row align-items-center text-center">
                            <div class="col-4">
                                <img src="../uploads/<?php echo e($m['home_logo']); ?> ?>" width="40" class="mb-2">
                                <div class="fw-bold"><?php echo e($m['home']); ?> ?></div>
                            </div>
                            <div class="col-4">
                                <h5><?php echo e($m['score_home']); ?> ?> : <?php echo e($m['score_away']); ?> ?></h5>
                                <span class="badge bg-<?php echo $m['status'] == 'live' ? 'danger' : 'secondary'; ?>"><?php echo strtoupper($m['status']); ?></span>
                            </div>
                            <div class="col-4">
                                <img src="../uploads/<?php echo e($m['away_logo']); ?> ?>" width="40" class="mb-2">
                                <div class="fw-bold"><?php echo e($m['away']); ?> ?></div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="index.php?id=<?php echo e($m['id']); ?> ?>" class="btn btn-outline-dark btn-sm">Match Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

require_once __DIR__ . '/../templates/footer.php'; ?>
