import { useState } from "react";
import { useNavigate } from "react-router-dom";
import bgCalanque from "../assets/bg-calanque.jpg";
import '../css/wizard-register.css';

export default function WizardRegister({ setUser }) {
  const [step, setStep] = useState(0);
  const [nom, setNom] = useState("");
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
        "http://localhost:8080/controllers/AuthController.php?action=register",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ nom, email, mot_de_passe: motDePasse }),
          credentials: "include"
        }
      );
      const data = await response.json();
      if (data.success) {
        setNom("");
        setEmail("");
        setMotDePasse("");
        setUser(data.user);
        navigate("/dashboard");
      } else {
        setMessage(data.error || "Erreur lors de l'inscription");
      }
    } catch (error) {
      setMessage("Erreur " + error.message);
    }
  };

  return (
    <div className="wizard-register-container">
      <div className="wizard-register-img">
        <img src={bgCalanque} alt="Inscription" />
      </div>
      <div className="wizard-register-form">
        <form onSubmit={handleSubmit}>
          <h2>Inscription</h2>
          <div className="wizard-steps">
            <div className={`wizard-step ${step === 0 ? 'active' : ''}`}>1</div>
            <div className={`wizard-step ${step === 1 ? 'active' : ''}`}>2</div>
            <div className={`wizard-step ${step === 2 ? 'active' : ''}`}>3</div>
          </div>
          {step === 0 && (
            <div className="wizard-field">
              <label>Nom</label>
              <input type="text" value={nom} onChange={e => setNom(e.target.value)} required autoFocus />
              <button type="button" className="wizard-next" onClick={nextStep}>Suivant</button>
            </div>
          )}
          {step === 1 && (
            <div className="wizard-field">
              <label>Email</label>
              <input type="email" value={email} onChange={e => setEmail(e.target.value)} required />
              <div className="wizard-actions">
                <button type="button" className="wizard-prev" onClick={prevStep}>Retour</button>
                <button type="button" className="wizard-next" onClick={nextStep}>Suivant</button>
              </div>
            </div>
          )}
          {step === 2 && (
            <div className="wizard-field">
              <label>Mot de passe</label>
              <input type="password" value={motDePasse} onChange={e => setMotDePasse(e.target.value)} required />
              <div className="wizard-actions">
                <button type="button" className="wizard-prev" onClick={prevStep}>Retour</button>
                <button type="submit" className="wizard-submit">S'inscrire</button>
              </div>
            </div>
          )}
          {message && <p className="wizard-error">{message}</p>}
        </form>
        <p className="wizard-login">Déjà inscrit ? <a href="/login">Se connecter</a></p>
      </div>
    </div>
  );
}
