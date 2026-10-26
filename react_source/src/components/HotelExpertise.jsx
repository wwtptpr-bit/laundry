import React from 'react';
import { motion } from 'framer-motion';
import { BedDouble, Layers, Droplets, Wind, Shirt, Hand } from 'lucide-react';

const Title = ({ children }) => (
  <motion.h2 
    initial={{ opacity: 0, y: 20 }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true, amount: 0.5 }}
    transition={{ duration: 0.5 }}
    className="text-4xl md:text-5xl font-serif font-bold mb-4"
  >
    {children}
  </motion.h2>
);

const HotelExpertise = () => {
  const linenTypes = [
    { icon: BedDouble, name: 'Sprei & Duvet Cover', description: 'Kebersihan sempurna untuk kenyamanan tamu.' },
    { icon: Layers, name: 'Handuk Mandi & Tangan', description: 'Lembut, menyerap, dan higienis.' },
    { icon: Droplets, name: 'Sarung Bantal & Guling', description: 'Bebas noda dan alergen.' },
    { icon: Wind, name: 'Keset & Jubah Mandi', description: 'Selalu segar dan siap pakai.' },
    { icon: Shirt, name: 'Seragam Staf', description: 'Menjaga citra profesional hotel.' },
    { icon: Hand, name: 'Taplak Meja & Serbet', description: 'Bersih cemerlang untuk F&B.' },
  ];

  return (
    <section id="hotel-expertise" className="py-20 px-4 section-bg water-effect" style={{'--ripple-delay': '2s'}}>
      <div className="container mx-auto">
        <div className="text-center mb-16">
          <Title>
            Laundry <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Hotel Expert</span>
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground max-w-3xl mx-auto"
          >
            Spesialis perawatan linen hotel untuk memastikan standar kebersihan dan kenyamanan tertinggi bagi tamu Anda.
          </motion.p>
        </div>

        <div className="grid md:grid-cols-2 gap-12 items-center">
          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8 }}
            className="relative"
          >
             <div className="relative z-10">
                <img class="w-full h-auto rounded-3xl shadow-2xl" alt="Stack of clean, white, folded hotel towels and bed linen" src="https://images.unsplash.com/photo-1496890185067-72d7d9af66b1" />
            </div>
            <div className="absolute -top-6 -left-6 w-72 h-72 bg-gradient-to-br from-primary/30 to-emerald-300/30 rounded-full blur-3xl opacity-50 -z-10"></div>
            <div className="absolute -bottom-6 -right-6 w-72 h-72 bg-gradient-to-br from-emerald-300/30 to-primary/30 rounded-full blur-3xl opacity-50 -z-10"></div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 50 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8, delay: 0.2 }}
          >
            <h3 className="text-3xl font-serif font-bold mb-6 text-foreground">
              Solusi Lengkap untuk Kebutuhan Linen Hotel
            </h3>
            <p className="text-lg text-muted-foreground mb-8 leading-relaxed">
              Kami menggunakan deterjen premium dan teknik pencucian modern yang terbukti menjaga kualitas bahan, memperpanjang usia linen, dan memberikan kelembutan ekstra.
            </p>
            <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
              {linenTypes.map((item, index) => (
                <motion.div
                  key={index}
                  initial={{ opacity: 0, y: 20 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: 0.3 + index * 0.1 }}
                  whileHover={{ scale: 1.05 }}
                  className="flex flex-col text-center items-center gap-3 p-4 rounded-xl shadow-md border bg-card"
                >
                  <motion.div 
                    whileHover={{ scale: 1.2 }}
                    className="w-12 h-12 bg-gradient-to-br from-secondary to-green-100 rounded-lg flex items-center justify-center flex-shrink-0"
                  >
                    <item.icon className="w-6 h-6 text-primary" />
                  </motion.div>
                  <span className="font-semibold text-foreground text-base">{item.name}</span>
                </motion.div>
              ))}
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
};

export default HotelExpertise;