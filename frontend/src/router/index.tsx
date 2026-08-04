import { BrowserRouter, Navigate, Route, Routes } from "react-router-dom";
import ProtectedRoute from "../presentation/components/ProtectedRoute";
import DashboardLayout from "../presentation/layouts/DashboardLayout";
import DashboardPage from "../presentation/pages/DashboardPage";
import LandingPage from "../presentation/pages/LandingPage";
import LoginPage from "../presentation/pages/LoginPage";
import ModulePage from "../presentation/pages/ModulePage";
import PatientsPage from "../presentation/pages/PatientsPage";
import NotificationsPage from "../presentation/pages/NotificationsPage";
import DoctorsPage from "../presentation/pages/DoctorsPage";
import NursesPage from "../presentation/pages/NursesPage";
import AppointmentsPage from "../presentation/pages/AppointmentsPage";
import EncounterPage from "../presentation/pages/EncounterPage";

export default function AppRouter() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<LandingPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route
          element={
            <ProtectedRoute>
              <DashboardLayout />
            </ProtectedRoute>
          }
        >
          <Route path="/dashboard" element={<DashboardPage />} />
          <Route path="/departments" element={<ModulePage module="Departments" />} />
          <Route path="/patients" element={<PatientsPage />} />
          <Route path="/appointments" element={<AppointmentsPage />} />
          <Route path="/encounter" element={<EncounterPage />} />
          <Route path="/doctors" element={<DoctorsPage />} />
          <Route path="/nurses" element={<NursesPage />} />
          <Route path="/notifications" element={<NotificationsPage />} />
          <Route path="/settings" element={<ModulePage module="Settings" />} />
        </Route>
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  );
}
