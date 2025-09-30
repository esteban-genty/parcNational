import bgHome from "../assets/bg-home.jpeg";
import '../css/root.css'
import '../css/home.css'

// Terrestrial Natual Ressource
import forest from '../assets/terrestrial/forêts.jpg'
import garrigue from '../assets/terrestrial/garrigue.jpg'
import rockyHabitat from '../assets/terrestrial/habitats-rocheux.jpg'

// Marine Natural Ressource
import coralligenous from '../assets/marine/coralligène.jpg'
import seaCave from '../assets/marine/grottes-sous-marines.jpg'
import posidonie from '../assets/marine/posidonie.jpg'


import { useState, useEffect } from 'react';
import './teaser.css';

export default function Home() {
  const [showTeaser, setShowTeaser] = useState(true);
  useEffect(() => {
    const timer = setTimeout(() => setShowTeaser(false), 4000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <div style={{position:'relative',minHeight:'100vh'}}>
      {showTeaser && (
        <div className="teaser-cinematic" style={{
          background: `radial-gradient(ellipse at center, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.85) 100%), url(${bgHome}) center/cover no-repeat`
        }}>
          <div className="teaser-left" />
          <div className="teaser-right" />
          <div className="teaser-center">
            <h1>Parc National des Calanques</h1>
            <p>Un écrin naturel entre mer et falaises, au cœur de Marseille.</p>
          </div>
        </div>
      )}
      <div style={{
        width: '100%',
        height: '320px',
        background: `linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.05) 100%), url(${bgHome}) center/cover no-repeat`,
        borderBottomLeftRadius: '32px',
        borderBottomRightRadius: '32px',
        boxShadow: '0 8px 32px #0005',
        opacity: showTeaser ? 0 : 1,
        transition: 'opacity 0.8s cubic-bezier(0.77,0,0.175,1)',
        position: 'relative',
        zIndex: 0
      }} />
      <div className="homepage-content" style={{
        filter: showTeaser ? 'blur(12px)' : 'none',
        pointerEvents: showTeaser ? 'none' : 'auto',
        opacity: showTeaser ? 0 : 1,
        transition: 'opacity 0.8s cubic-bezier(0.77,0,0.175,1)',
        position: showTeaser ? 'fixed' : 'relative',
        top: showTeaser ? 0 : 'auto',
        left: showTeaser ? 0 : 'auto',
        width: showTeaser ? '100vw' : 'auto',
        height: showTeaser ? '100vh' : 'auto',
        zIndex: showTeaser ? 1 : 'auto',
        background: showTeaser ? 'transparent' : 'none',
      }}>
        <section>
          <h4 id="terrestrial">Ressources terrestres</h4>
          <section className="terrestrial-natural-resource">
            <div>
              <img src={forest} alt="Forêt" />
              <h5>Forêt</h5>
            </div>
            <div>
              <img src={garrigue} alt="Garrigue" />
              <h5>Garrigue et maquis</h5>
            </div>
            <div>
              <img src={rockyHabitat} alt="Habitats Rocheux" />
              <h5>Habitats Rocheux</h5>
            </div>
          </section>

          <h4 id="marine">Ressources marines</h4>
          <section className="marine-natural-resource">
            <div>
              <img src={coralligenous} alt="Coralligène" />
              <h5>Coralligène</h5>
            </div>
            <div>
              <img src={seaCave} alt="Grotte sous-marine" />
              <h5>Grotte sous-marine</h5>
            </div>
            <div>
              <img src={posidonie} alt="Posidonie" />
              <h5>Posidonie</h5>
            </div>
          </section>

          <section className="register-home-section">
            <div className="register-content">
              <h4 id="register-home">Rejoignez-nous !</h4>
              <p>Inscrivez-vous dès maintenant pour découvrir toutes nos actualités et avantages.</p>
              <button className="register-btn">S’inscrire</button>
            </div>
          </section>
        </section>
      </div>
    </div>
  );
}
