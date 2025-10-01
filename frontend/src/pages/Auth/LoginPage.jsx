import { useEffect } from "react";
import LoginForm from "../../components/LoginForm";
import '../../css/root.css'
import '../../css/login.css'

export default function LoginPage({ setUser }) {
  useEffect(() => {

    document.body.classList.add("login-body");

    return () => {
      document.body.classList.remove("login-body");
    };
  }, []);

  return (
    <section className="login-section">
      <LoginForm setUser={setUser} /> 
    </section>
  );
}
