import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Menu, X, ChevronDown, ShowerHead as WashingMachine } from 'lucide-react';
import { Button } from '@/components/ui/button';

const Header = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isTrayOpen, setIsTrayOpen] = useState(false);

  const scrollToSection = (id) => {
    const element = document.getElementById(id);
    if (element) {
      const headerOffset = 100;
      const elementPosition = element.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
      
      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
      setIsMenuOpen(false);
      setIsTrayOpen(false);
    }
  };

  const trayLinks = [
    { id: 'services', label: 'Layanan' },
    { id: 'company-profile', label: 'Profil Kami' },
    { id: 'pricing', label: 'Harga' },
    { id: 'contact', label: 'Hubungi Kami' },
  ];

  return (
    <motion.header 
      initial={{ y: -100 }} 
      animate={{ y: 0 }} 
      className="fixed top-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-lg border-b"
    >
      <div className="container mx-auto px-4 py-4">
        <div className="flex items-center justify-between">
          <motion.div 
            whileHover={{ scale: 1.05 }} 
            className="flex items-center gap-3 cursor-pointer" 
            onClick={() => scrollToSection('hero')}
          >
            <div className="w-12 h-12 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
              <WashingMachine className="w-7 h-7 text-white" />
            </div>
            <span className="text-2xl font-serif font-bold text-foreground">CROWN & LUCKY</span>
          </motion.div>

          <nav className="hidden md:flex items-center gap-2">
            <div className="relative" onMouseEnter={() => setIsTrayOpen(true)} onMouseLeave={() => setIsTrayOpen(false)}>
              <button className="flex items-center gap-1 text-foreground hover:text-primary transition-colors font-medium p-2 rounded-md">
                Menu <ChevronDown className="w-4 h-4" />
              </button>
              <AnimatePresence>
                {isTrayOpen && (
                  <motion.div
                    initial={{ opacity: 0, y: 10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: 10 }}
                    className="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2"
                  >
                    {trayLinks.map(link => (
                      <button key={link.id} onClick={() => scrollToSection(link.id)} className="block w-full text-left px-4 py-2 text-foreground hover:bg-secondary">
                        {link.label}
                      </button>
                    ))}
                  </motion.div>
                )}
              </AnimatePresence>
            </div>
            <Button onClick={() => scrollToSection('contact')} className="bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90 transition-opacity ml-2">
              Pesan Sekarang
            </Button>
          </nav>

          <button className="md:hidden" onClick={() => setIsMenuOpen(!isMenuOpen)}>
            {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>
      </div>
      
      <AnimatePresence>
        {isMenuOpen && (
          <motion.div 
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            className="md:hidden bg-background/95 backdrop-blur-sm border-t"
          >
            <nav className="flex flex-col gap-2 p-4">
              {trayLinks.map(link => (
                <button key={link.id} onClick={() => scrollToSection(link.id)} className="text-foreground hover:text-primary transition-colors font-medium text-left py-2">
                  {link.label}
                </button>
              ))}
              <Button onClick={() => scrollToSection('contact')} className="bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90 w-full mt-2">
                Pesan Sekarang
              </Button>
            </nav>
          </motion.div>
        )}
      </AnimatePresence>
    </motion.header>
  );
};
export default Header;