import RegisterForm from "../components/RegisterForm";
import '../css/root.css'
import '../css/register.css'

export default function RegisterPage({ setUser }) {
  return (
    <section className="register-section">
      <RegisterForm setUser={setUser} />
    </section>
  );
}
