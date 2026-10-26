import React from 'react';
import { motion } from 'framer-motion';
import { Eye, Shield, Wrench, Sparkles } from 'lucide-react';

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

const GordynExpertise = () => {
  const services = [
    { icon: Eye, title: 'Cuci Gordyn', description: 'Mengembalikan kesegaran warna gordyn Anda.' },
    { icon: Shield, title: 'Cuci Vertical/Roller Blind', description: 'Pembersihan detail untuk setiap bilah blind.' },
    { icon: Wrench, title: 'Perbaikan & Servis', description: 'Memperbaiki kerusakan pada rel dan mekanisme.' },
    { icon: Sparkles, title: 'Pasang Baru', description: 'Instalasi rapi dan presisi untuk ruangan Anda.' },
  ];

  return (
    <section id="gordyn-expertise" className="py-20 px-4 section-bg bg-secondary/30 water-effect" style={{'--ripple-delay': '3s'}}>
      <div className="container mx-auto">
        <div className="text-center mb-16">
          <Title>
            Spesialis <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Gordyn & Blind</span>
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground max-w-3xl mx-auto"
          >
            Layanan lengkap dari pencucian hingga perbaikan untuk gordyn, vertical blind, dan roller blind Anda.
          </motion.p>
        </div>
        
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {services.map((service, index) => (
                <motion.div
                    key={index}
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ delay: index * 0.1 }}
                    whileHover={{ y: -5, scale: 1.02 }}
                    className="bg-green-100/50 dark:bg-green-900/30 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-primary/20 text-center p-8 flex flex-col items-center justify-center"
                >
                    <motion.div 
                        whileHover={{ scale: 1.1, rotate: 10 }}
                        className="w-16 h-16 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md"
                    >
                        <service.icon className="w-8 h-8 text-white" />
                    </motion.div>
                    <h3 className="text-xl font-bold mb-2 text-foreground dark:text-white">{service.title}</h3>
                    <p className="text-muted-foreground dark:text-gray-300">{service.description}</p>
                </motion.div>
            ))}
        </div>
      </div>
    </section>
  );
};

export default GordynExpertise;