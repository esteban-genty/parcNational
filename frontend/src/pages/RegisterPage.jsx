import { useEffect } from "react";
import RegisterForm from "../components/RegisterForm";
import '../css/root.css'
import '../css/register.css'

export default function RegisterPage({ setUser }) {
  useEffect(() => {

    document.body.classList.add("register-body");

    return () => {
      document.body.classList.remove("register-body");
    };
  }, []);

  return (
    <section className="register-section">
      <RegisterForm setUser={setUser} /> 
    </section>
  );
}
