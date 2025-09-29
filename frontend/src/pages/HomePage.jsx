import bgHome from "../assets/bg-home.jpeg"
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

export default function Home() {
  return (
    <section className="content-home">
    <section className="header-content">
        <div className="bg-img-home">
          <img src={bgHome} alt="" />
          <div className="text-img-home">
            <h1>Les Calanques vous invitent</h1>
            <p>Un paradis naturel à préserver, à contempler et à explorer en toute harmonie avec la nature.</p>
          </div>
        </div>
    </section>

      <h4 id="natural">Ressources Terrestres</h4>
      <section className="terrestrial-natural-resource">
          <div>
            <img src={forest} alt="Forêts" />
            <h5>Forêts</h5>
          </div>
          <div>
            <img src={garrigue} alt="Garrigue" />
            <h5>Garrigue et maquis</h5>
          </div>
          <div>
            <img src={rockyHabitat} alt="Habitats Rocheux" />
            <h5>"Habitats Rocheux</h5>
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
            <h5>"Posidonie</h5>
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
  );
}
