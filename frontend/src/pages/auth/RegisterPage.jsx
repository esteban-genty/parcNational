import { useEffect } from "react";
import WizardRegister from "../../components/WizardRegister";
import '../../css/root.css'
import '../../css/register.css'

export default function RegisterPage({ setUser }) {
  useEffect(() => {

    document.body.classList.add("register-body");

    return () => {
      document.body.classList.remove("register-body");
    };
  }, []);

  return (
    <section className="register-section">
      <WizardRegister setUser={setUser} />
    </section>
  );
}
