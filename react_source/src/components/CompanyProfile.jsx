import React from 'react';
import { motion } from 'framer-motion';
import { Hotel, Star, Award } from 'lucide-react';

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

const CompanyProfile = () => {
  const stats = [
    { icon: Award, value: '20+ Tahun', label: 'Pengalaman' },
    { icon: Hotel, value: '30+', label: 'Mitra Hotel di Surabaya' },
    { icon: Star, value: 'Ribuan', label: 'Pelanggan Puas' },
  ];

  return (
    <section id="company-profile" className="py-20 px-4 section-bg water-effect" style={{'--ripple-delay': '0s'}}>
      <div className="container mx-auto">
        <div className="text-center mb-16">
          <Title>
            Profil <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Perusahaan</span>
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground max-w-2xl mx-auto"
          >
            CROWN & LUCKY Laundry: Mitra terpercaya untuk kebersihan dan kualitas sejak 1997.
          </motion.p>
        </div>

        <div className="grid md:grid-cols-2 gap-12 items-center mb-16">
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8 }}
          >
            <h3 className="text-3xl font-serif font-bold mb-6 text-foreground">
              Kualitas Terjamin, Kepercayaan Terbina
            </h3>
            <p className="text-lg text-muted-foreground mb-4 leading-relaxed">
              Berdiri sejak tahun 1997, CROWN & LUCKY Laundry telah mendedikasikan diri dalam melayani kebutuhan laundry dengan standar tertinggi. Pengalaman kami yang luas di segala bidang laundry menjadikan kami pilihan utama bagi individu, bisnis, hingga hotel-hotel terkemuka.
            </p>
            <p className="text-lg text-muted-foreground leading-relaxed">
              Dengan tim profesional, teknologi modern, dan komitmen terhadap kepuasan pelanggan, kami memastikan setiap helai pakaian Anda ditangani dengan cermat dan kembali dalam kondisi terbaik.
            </p>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="relative"
          >
            <div className="relative z-10">
              <img class="w-full h-auto rounded-3xl shadow-2xl" alt="Modern and clean professional laundry facility with industrial machines" src="https://images.unsplash.com/photo-1638949493140-edb10b7be2f3" />
            </div>
            <div className="absolute -top-6 -right-6 w-72 h-72 bg-gradient-to-br from-primary/30 to-emerald-300/30 rounded-full blur-3xl opacity-50 -z-10"></div>
            <div className="absolute -bottom-6 -left-6 w-72 h-72 bg-gradient-to-br from-emerald-300/30 to-primary/30 rounded-full blur-3xl opacity-50 -z-10"></div>
          </motion.div>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          {stats.map((stat, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, scale: 0.8 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.15 }}
              whileHover={{ scale: 1.05, y: -5 }}
              className="bg-card rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300 border"
            >
              <motion.div 
                whileHover={{ scale: 1.15, rotate: -10 }}
                transition={{ type: 'spring', stiffness: 300 }}
                className="w-16 h-16 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4"
              >
                <stat.icon className="w-8 h-8 text-white" />
              </motion.div>
              <p className="text-4xl font-bold bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent mb-2">{stat.value}</p>
              <p className="text-lg font-semibold text-foreground">{stat.label}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default CompanyProfile;