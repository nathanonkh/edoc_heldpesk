<?php
header('Content-Type: text/html; charset=UTF-8');

require_once 'config/config.php';
session_start();

require_once 'core/Session.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'core/Router.php';

require_once 'helpers/Auth.php';
require_once 'helpers/FileUpload.php';
require_once 'helpers/Notification.php';
require_once 'helpers/functions.php';

require_once 'models/UserModel.php';
require_once 'models/DocumentModel.php';
require_once 'models/CooperativeModel.php';
require_once 'models/NotificationModel.php';
require_once 'models/ReportModel.php';
require_once 'models/IssueModel.php';
require_once 'models/IssueReportModel.php';
require_once 'models/AnnouncementModel.php';
require_once 'models/VideoModel.php';

require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/DocumentController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/CooperativeController.php';
require_once 'controllers/NotificationController.php';
require_once 'controllers/ReportController.php';
require_once 'controllers/IssueController.php';
require_once 'controllers/IssueReportController.php';
require_once 'controllers/AnnouncementController.php';

$db = new Model();
$router = new Router($db);
$router->dispatch();
