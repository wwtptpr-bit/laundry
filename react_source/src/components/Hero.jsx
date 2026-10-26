import React from 'react';
import { motion } from 'framer-motion';
import { Button } from '@/components/ui/button';
import { Zap, Clock, Shield } from 'lucide-react';

const Hero = () => {
  const scrollToOrder = () => {
    const element = document.getElementById('contact');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  };

  return (
    <section 
      id="hero" 
      className="relative pt-32 pb-20 px-4 overflow-hidden"
      style={{
        backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23a3b18a' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`
      }}
    >
      <div className="container mx-auto">
        <div className="grid md:grid-cols-2 gap-12 items-center">
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8 }}
          >
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.2 }}
              className="inline-block mb-4 px-4 py-2 bg-secondary rounded-full"
            >
              <span className="text-secondary-foreground font-semibold text-sm">✨ Berpengalaman Sejak 1997</span>
            </motion.div>

            <h1 className="text-5xl md:text-6xl font-serif font-bold mb-6 leading-tight">
              Pengalaman Menciptakan
              <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent"> Kesempurnaan.</span>
            </h1>

            <p className="text-xl text-muted-foreground mb-8 leading-relaxed">
              Nikmati layanan laundry cepat dan andal. Pakaian Anda bersih, wangi, dan rapi sempurna, didukung oleh puluhan tahun pengalaman.
            </p>

            <div className="flex flex-wrap gap-4 mb-8">
              <Button
                onClick={scrollToOrder}
                size="lg"
                className="bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90 transition-opacity text-primary-foreground text-lg px-8"
              >
                Pesan Sekarang
              </Button>
              <Button
                onClick={() => {
                  const element = document.getElementById('services');
                  if (element) element.scrollIntoView({ behavior: 'smooth' });
                }}
                size="lg"
                variant="outline"
                className="text-lg px-8 border-2 border-primary text-primary hover:bg-secondary"
              >
                Lihat Layanan
              </Button>
            </div>

            <div className="grid grid-cols-3 gap-6">
              <motion.div whileHover={{ scale: 1.05 }} className="text-center">
                <div className="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-full flex items-center justify-center mx-auto mb-2">
                  <Zap className="w-6 h-6 text-white" />
                </div>
                <p className="text-sm font-semibold text-foreground">Layanan Cepat</p>
              </motion.div>

              <motion.div whileHover={{ scale: 1.05 }} className="text-center">
                <div className="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-full flex items-center justify-center mx-auto mb-2">
                  <Shield className="w-6 h-6 text-white" />
                </div>
                <p className="text-sm font-semibold text-foreground">Garansi Bersih</p>
              </motion.div>

              <motion.div whileHover={{ scale: 1.05 }} className="text-center">
                <div className="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-full flex items-center justify-center mx-auto mb-2">
                  <Clock className="w-6 h-6 text-white" />
                </div>
                <p className="text-sm font-semibold text-foreground">Tepat Waktu</p>
              </motion.div>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="relative"
          >
            <div className="relative z-10">
              <img class="w-full h-auto rounded-3xl shadow-2xl" alt="Piles of neatly folded clean clothes and towels ready for delivery" src="https://images.unsplash.com/photo-1572501403253-c113f1d6c7fe" />
            </div>
            <div className="absolute -top-6 -right-6 w-72 h-72 bg-gradient-to-br from-primary/30 to-emerald-300/30 rounded-full blur-3xl opacity-50 -z-10"></div>
            <div className="absolute -bottom-6 -left-6 w-72 h-72 bg-gradient-to-br from-emerald-300/30 to-primary/30 rounded-full blur-3xl opacity-50 -z-10"></div>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

export default Hero;