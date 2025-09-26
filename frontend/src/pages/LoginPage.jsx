import LoginForm from "../components/LoginForm";

export default function LoginPage({ setUser }) {
  return (
    <div>
      <LoginForm setUser={setUser} /> 
    </div>
  );
}
