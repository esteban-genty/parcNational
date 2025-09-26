import LoginForm from "../components/LoginForm";
import '../css/root.css'
import '../css/login.css'

export default function LoginPage({ setUser }) {
  return (
    <section className="login-section">
      <LoginForm setUser={setUser} /> 
    </section>
  );
}
