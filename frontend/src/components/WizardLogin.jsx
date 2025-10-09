import { useState } from "react";
import { useNavigate } from "react-router-dom";
import bgCalanque from "../assets/bg-connexion.jpg";
import '../css/wizard-login.css';

export default function WizardLogin({ setUser }) {
  const [step, setStep] = useState(0);
  const [email, setEmail] = useState("");
  const [motDePasse, setMotDePasse] = useState("");
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  const nextStep = () => setStep((s) => Math.min(s + 1, 2));
  const prevStep = () => setStep((s) => Math.max(s - 1, 0));

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(
        "http://localhost:8080/api/auth/login.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, mot_de_passe: motDePasse }),
          credentials: "include",
        }
      );
      const data = await response.json();
      
      // L'API retourne { success: true, data: { user, tokens } }
      if (data.success && data.data?.user) {
        setUser(data.data.user);
        
        // Stocker les tokens JWT
        if (data.data.tokens?.access_token) {
          localStorage.setItem('access_token', data.data.tokens.access_token);
          localStorage.setItem('refresh_token', data.data.tokens.refresh_token);
        }
        
        // Vérification rôle admin
        if (data.data.user.role === 'admin') {
          localStorage.setItem('isAdmin', 'true');
        } else {
          localStorage.removeItem('isAdmin');
        }
        
        navigate("/dashboard");
      } else {
        setMessage(data.message || data.error || "Erreur lors de la connexion");
      }
    } catch (error) {
      setMessage("Erreur : " + error.message);
    }
  };

  return (
    <div className="wizard-login-container">
      <div className="wizard-login-img">
        <img src={bgCalanque} alt="Connexion" />
      </div>
      <div className="wizard-login-form">
        <form onSubmit={handleSubmit}>
          <h2>Connexion</h2>
          {/* Badge admin si email admin détecté */}
          {email && email.toLowerCase().includes('admin') && (
            <div style={{marginBottom:'12px',color:'#007aff',fontWeight:'bold'}}>Connexion administrateur</div>
          )}
          <div className="wizard-steps">
            <div className={`wizard-step ${step === 0 ? 'active' : ''}`}>1</div>
            <div className={`wizard-step ${step === 1 ? 'active' : ''}`}>2</div>
          </div>
          {step === 0 && (
            <div className="wizard-field">
              <label>Email</label>
              <input type="email" value={email} onChange={e => setEmail(e.target.value)} required autoFocus />
              <button type="button" className="wizard-next" onClick={nextStep}>Suivant</button>
            </div>
          )}
          {step === 1 && (
            <div className="wizard-field">
              <label>Mot de passe</label>
              <input type="password" value={motDePasse} onChange={e => setMotDePasse(e.target.value)} required />
              <div className="wizard-actions">
                <button type="button" className="wizard-prev" onClick={prevStep}>Retour</button>
                <button type="submit" className="wizard-submit">Se connecter</button>
              </div>
            </div>
          )}
          {message && <p className="wizard-error">{message}</p>}
        </form>
        <p className="wizard-register">Pas encore inscrit ? <a href="/register">Créer un compte</a></p>
      </div>
    </div>
  );
}
