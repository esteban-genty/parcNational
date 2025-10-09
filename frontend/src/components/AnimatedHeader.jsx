import { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import icons from '../utils/icons';
import '../css/animations.css';

export default function Header({ user, onLogout }) {
  const [scrolled, setScrolled] = useState(false);
  const [scrollProgress, setScrollProgress] = useState(0);
  const navigate = useNavigate();

  useEffect(() => {
    const handleScroll = () => {
      // Détection scroll
      const offset = window.scrollY;
      setScrolled(offset > 50);
      
      // Progression scroll
      const height = document.documentElement.scrollHeight - window.innerHeight;
      const progress = (offset / height) * 100;
      setScrollProgress(progress);
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const handleLogoutClick = async () => {
    await onLogout();
    navigate('/');
  };

  return (
    <>
      {/* Barre de progression du scroll */}
      <div 
        className="scroll-indicator" 
        style={{ width: `${scrollProgress}%` }}
      />
      
      {/* Header avec transition transparente */}
      <header className={`header-fixed ${scrolled ? 'scrolled' : 'transparent'}`}>
        <div className="container-fluid" style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          padding: '1rem 2rem'
        }}>
          {/* Logo animé */}
          <Link to="/" style={{ textDecoration: 'none' }}>
            <h1 className="text-gradient" style={{
              fontSize: '1.5rem',
              fontWeight: '800',
              transition: 'all 0.3s ease',
              cursor: 'pointer'
            }}>
              <FontAwesomeIcon icon={icons.mountain} />               <FontAwesomeIcon icon={icons.mountain} /> Parc National
            </h1>
          </Link>

          {/* Navigation fluide */}
          <nav style={{
            display: 'flex',
            gap: '2rem',
            alignItems: 'center'
          }}>
            {user ? (
              <>
                <Link to="/dashboard" className="btn btn-primary">
                  {user.role === 'admin' ? (
                    <><FontAwesomeIcon icon={icons.cog} /> Admin</>
                  ) : (
                    <><FontAwesomeIcon icon={icons.user} /> Profil</>
                  )}
                </Link>
                <button 
                  onClick={handleLogoutClick}
                  className="btn"
                  style={{
                    background: 'transparent',
                    border: '2px solid var(--parc-bleu)',
                    color: 'var(--parc-bleu)'
                  }}
                >
                  Déconnexion
                </button>
              </>
            ) : (
              <>
                <Link to="/login" className="btn" style={{
                  background: 'transparent',
                  border: '2px solid var(--parc-turquoise)',
                  color: 'var(--parc-turquoise)'
                }}>
                  Connexion
                </Link>
                <Link to="/register" className="btn btn-primary">
                  Inscription
                </Link>
              </>
            )}
          </nav>
        </div>
      </header>

      {/* Spacer pour éviter que le contenu passe sous le header */}
      <div style={{ height: scrolled ? '70px' : '90px', transition: 'height 0.3s ease' }} />
    </>
  );
}
