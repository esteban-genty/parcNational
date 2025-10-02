import { useEffect } from "react";
import WizardLogin from "../components/WizardLogin";
import '../css/root.css'
import '../css/login.css'

export default function LoginPage({ setUser }) {
  useEffect(() => {

    document.body.classList.add("login-body");

    return () => {
      document.body.classList.remove("login-body");
    };
  }, []);

  return (
    <section className="login-section">
      <WizardLogin setUser={setUser} />
    </section>
  );
}
