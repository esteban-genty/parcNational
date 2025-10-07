import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import bgHome from '../assets/bg-home.jpeg';
import '../css/animations.css';

// Ressources terrestres
import forest from '../assets/terrestrial/forêts.jpg';
import garrigue from '../assets/terrestrial/garrigue.jpg';
import rockyHabitat from '../assets/terrestrial/habitats-rocheux.jpg';

// Ressources marines
import coralligenous from '../assets/marine/coralligène.jpg';
import seaCave from '../assets/marine/grottes-sous-marines.jpg';
import posidonie from '../assets/marine/posidonie.jpg';

export default function HomePage() {
  const [showTeaser, setShowTeaser] = useState(true);
  const [visibleSections, setVisibleSections] = useState([]);

  useEffect(() => {
    // Teaser d'ouverture
    const timer = setTimeout(() => setShowTeaser(false), 3500);

    // Observer pour animations au scroll
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            setVisibleSections((prev) => [...prev, entry.target.id]);
          }
        });
      },
      { threshold: 0.1 }
    );

    // Observer toutes les sections
    document.querySelectorAll('[data-animate]').forEach((el) => {
      observer.observe(el);
    });

    return () => {
      clearTimeout(timer);
      observer.disconnect();
    };
  }, []);

  if (showTeaser) {
    return (
      <div style={{
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        background: `linear-gradient(135deg, rgba(30,77,123,0.9), rgba(46,169,200,0.8)), url(${bgHome}) center/cover`,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: 9999,
        animation: 'fadeOut 1s ease 2.5s forwards'
      }}>
        <style>{`
          @keyframes fadeOut {
            to {
              opacity: 0;
              visibility: hidden;
            }
          }
        `}</style>
        <div style={{
          textAlign: 'center',
          color: 'white',
          animation: 'scaleIn 1.2s ease-out'
        }}>
          <h1 style={{ fontSize: '4rem', fontWeight: '900', marginBottom: '1rem' }}>
            🏞️ Parc National des Calanques
          </h1>
          <p style={{ fontSize: '1.5rem', fontWeight: '300' }}>
            Un écrin naturel entre mer et falaises
          </p>
        </div>
      </div>
    );
  }

  return (
    <div style={{ overflow: 'hidden' }}>
      {/* Hero Section */}
      <section style={{
        height: '90vh',
        background: `linear-gradient(135deg, rgba(30,77,123,0.7), rgba(46,169,200,0.5)), url(${bgHome}) center/cover fixed`,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        color: 'white',
        textAlign: 'center',
        position: 'relative',
        borderBottomLeftRadius: '50px',
        borderBottomRightRadius: '50px',
        animation: 'fadeInUp 1s ease-out'
      }}>
        <div style={{ zIndex: 1 }}>
          <h2 style={{
            fontSize: '3.5rem',
            fontWeight: '900',
            marginBottom: '2rem',
            textShadow: '0 4px 20px rgba(0,0,0,0.3)'
          }}>
            Découvrez la Beauté Sauvage
          </h2>
          <p style={{
            fontSize: '1.3rem',
            marginBottom: '3rem',
            maxWidth: '600px',
            margin: '0 auto 3rem'
          }}>
            Entre terre et mer, explorez un patrimoine naturel unique
          </p>
          <Link to="/register" className="btn" style={{
            background: 'white',
            color: 'var(--parc-bleu)',
            padding: '1rem 3rem',
            fontSize: '1.2rem',
            fontWeight: '700',
            boxShadow: '0 8px 30px rgba(0,0,0,0.2)'
          }}>
            Commencer l'Aventure 🚀
          </Link>
        </div>
      </section>

      {/* Ressources Terrestres */}
      <section 
        id="terrestrial"
        data-animate
        className={visibleSections.includes('terrestrial') ? 'fade-in' : ''}
        style={{
          padding: '6rem 2rem',
          background: 'linear-gradient(180deg, #f8f9fa 0%, white 100%)'
        }}
      >
        <div className="container-fluid">
          <h3 className="text-gradient" style={{
            fontSize: '2.5rem',
            textAlign: 'center',
            marginBottom: '3rem',
            fontWeight: '800'
          }}>
            🌿 Ressources Terrestres
          </h3>
          
          <div className="grid grid-3">
            {[
              { img: forest, title: 'Forêts', desc: 'Écosystèmes riches et diversifiés' },
              { img: garrigue, title: 'Garrigue & Maquis', desc: 'Végétation méditerranéenne typique' },
              { img: rockyHabitat, title: 'Habitats Rocheux', desc: 'Formations géologiques spectaculaires' }
            ].map((item, i) => (
              <div key={i} className="card" style={{
                overflow: 'hidden',
                animationDelay: `${i * 0.2}s`
              }}>
                <img 
                  src={item.img} 
                  alt={item.title}
                  style={{
                    width: '100%',
                    height: '250px',
                    objectFit: 'cover',
                    borderRadius: '12px',
                    marginBottom: '1rem',
                    transition: 'transform 0.5s ease'
                  }}
                  className="img-fluid"
                />
                <h4 style={{
                  fontSize: '1.5rem',
                  color: 'var(--parc-vert)',
                  marginBottom: '0.5rem'
                }}>
                  {item.title}
                </h4>
                <p style={{ color: 'var(--parc-gris)' }}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Ressources Marines */}
      <section 
        id="marine"
        data-animate
        className={visibleSections.includes('marine') ? 'fade-in' : ''}
        style={{
          padding: '6rem 2rem',
          background: 'linear-gradient(180deg, white 0%, #e8f4f8 100%)'
        }}
      >
        <div className="container-fluid">
          <h3 className="text-gradient" style={{
            fontSize: '2.5rem',
            textAlign: 'center',
            marginBottom: '3rem',
            fontWeight: '800'
          }}>
            🌊 Ressources Marines
          </h3>
          
          <div className="grid grid-3">
            {[
              { img: coralligenous, title: 'Coralligène', desc: 'Récifs sous-marins colorés' },
              { img: seaCave, title: 'Grottes Sous-marines', desc: 'Cavités mystérieuses' },
              { img: posidonie, title: 'Posidonie', desc: 'Prairies marines essentielles' }
            ].map((item, i) => (
              <div key={i} className="card" style={{
                overflow: 'hidden',
                animationDelay: `${i * 0.2}s`
              }}>
                <img 
                  src={item.img} 
                  alt={item.title}
                  style={{
                    width: '100%',
                    height: '250px',
                    objectFit: 'cover',
                    borderRadius: '12px',
                    marginBottom: '1rem'
                  }}
                  className="img-fluid"
                />
                <h4 style={{
                  fontSize: '1.5rem',
                  color: 'var(--parc-turquoise)',
                  marginBottom: '0.5rem'
                }}>
                  {item.title}
                </h4>
                <p style={{ color: 'var(--parc-gris)' }}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Final */}
      <section style={{
        padding: '6rem 2rem',
        background: 'linear-gradient(135deg, var(--parc-bleu), var(--parc-turquoise))',
        color: 'white',
        textAlign: 'center',
        borderTopLeftRadius: '50px',
        borderTopRightRadius: '50px'
      }}>
        <h3 style={{ fontSize: '2.5rem', marginBottom: '2rem', fontWeight: '800' }}>
          Prêt à Explorer ?
        </h3>
        <p style={{ fontSize: '1.2rem', marginBottom: '3rem', maxWidth: '600px', margin: '0 auto 3rem' }}>
          Inscrivez-vous dès maintenant pour réserver vos visites et campings
        </p>
        <Link to="/register" className="btn" style={{
          background: 'white',
          color: 'var(--parc-bleu)',
          padding: '1rem 3rem',
          fontSize: '1.2rem'
        }}>
          Inscription Gratuite ✨
        </Link>
      </section>
    </div>
  );
}
