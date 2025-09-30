import { Navigate } from "react-router-dom";

export default function ProtectedRoute({ userRole, children }) {
  return userRole === "admin" ? children : <Navigate to="/tableau-bord" replace />;
}
