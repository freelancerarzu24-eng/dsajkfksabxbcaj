<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_match'])) {
    $id = (int)$_POST['id'];
    $team_home_id = (int)$_POST['team_home_id'];
    $team_away_id = (int)$_POST['team_away_id'];
    $match_date = $_POST['match_date'];
    $tournament_name = trim($_POST['tournament_name']);
    $status = $_POST['status'];
    $score_home = (int)$_POST['score_home'];
    $score_away = (int)$_POST['score_away'];
    if ($id > 0) {
        $db->query("UPDATE matches SET team_home_id = :team_home_id, team_away_id = :team_away_id, match_date = :match_date, tournament_name = :tournament_name, status = :status, score_home = :score_home, score_away = :score_away WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO matches (team_home_id, team_away_id, match_date, tournament_name, status, score_home, score_away) VALUES (:team_home_id, :team_away_id, :match_date, :tournament_name, :status, :score_home, :score_away)");
    }
    $db->bind(':team_home_id', $team_home_id);
    $db->bind(':team_away_id', $team_away_id);
    $db->bind(':match_date', $match_date);
    $db->bind(':tournament_name', $tournament_name);
    $db->bind(':status', $status);
    $db->bind(':score_home', $score_home);
    $db->bind(':score_away', $score_away);
    $db->execute();
}
$matches = $db->query("SELECT m.*, t1.name_en as home_team, t2.name_en as away_team FROM matches m JOIN teams t1 ON m.team_home_id = t1.id JOIN teams t2 ON m.team_away_id = t2.id ORDER BY m.match_date DESC");
$matches = $db->resultSet();
$teams = $db->query("SELECT id, name_en FROM teams");
$teams = $db->resultSet();
$page_title = "Manage Matches";
$active_page = "matches";
require_once __DIR__ . '/includes/header.php';
?>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <form method="POST"> <?php csrf_field(); ?>
                <input type="hidden" name="id" id="match_id" value="0">
                <div class="mb-3"><label>Home Team</label><select name="team_home_id" id="team_home" class="form-select"><?php foreach($teams as $t) echo "<option value='{$t['id']}'>{$t['name_en']}</option>"; ?></select></div>
                <div class="mb-3"><label>Away Team</label><select name="team_away_id" id="team_away" class="form-select"><?php foreach($teams as $t) echo "<option value='{$t['id']}'>{$t['name_en']}</option>"; ?></select></div>
                <div class="mb-3"><label>Date</label><input type="datetime-local" name="match_date" id="m_date" class="form-control" required></div>
                <div class="mb-3"><label>Tournament</label><input type="text" name="tournament_name" id="m_tour" class="form-control"></div>
                <div class="row mb-3"><div class="col"><label>Score H</label><input type="number" name="score_home" id="s_home" class="form-control" value="0"></div><div class="col"><label>Score A</label><input type="number" name="score_away" id="s_away" class="form-control" value="0"></div></div>
                <div class="mb-3"><label>Status</label><select name="status" id="m_status" class="form-select"><option value="upcoming">Upcoming</option><option value="live">Live</option><option value="completed">Completed</option></select></div>
                <button type="submit" name="save_match" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table bg-white"><thead><tr><th>Date</th><th>Teams</th><th>Score</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody><?php foreach($matches as $m): ?>
            <tr><td><?php echo date('M d, H:i', strtotime($m['match_date'])); ?></td><td><?php echo e($m['home_team']); ?> ?> vs <?php echo e($m['away_team']); ?> ?></td><td><?php echo e($m['score_home']); ?> ?> - <?php echo e($m['score_away']); ?> ?></td><td><?php echo e($m['status']); ?> ?></td><td><button class="btn btn-sm btn-info" onclick='editMatch(<?php echo json_encode($m); ?>)'>Edit</button></td></tr>
        <?php endforeach; ?></tbody></table>
    </div>
</div>
<script>
function editMatch(m) {
    document.getElementById('match_id').value = m.id;
    document.getElementById('team_home').value = m.team_home_id;
    document.getElementById('team_away').value = m.team_away_id;
    document.getElementById('m_date').value = m.match_date.replace(' ', 'T').substring(0, 16);
    document.getElementById('m_tour').value = m.tournament_name;
    document.getElementById('s_home').value = m.score_home;
    document.getElementById('s_away').value = m.score_away;
    document.getElementById('m_status').value = m.status;
}
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
