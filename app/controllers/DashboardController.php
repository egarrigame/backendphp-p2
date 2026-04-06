<?php
class DashboardController extends Controller {
    public function index() {
        $this->requireLogin();
        $this->view('dashboard');
    }
}
