#ifndef CONFANOMALIESONORE_H
#define CONFANOMALIESONORE_H

#include <QTime>

class ConfAnomalieSonore{

public:
    explicit ConfAnomalieSonore(QTime heure_debut, QTime heure_fin, unsigned int seuil, unsigned int duree, unsigned int id_conf_as = 0, bool debug = false);
    explicit ConfAnomalieSonore();
    unsigned int get_id_conf_as() const;
    QTime get_heure_debut() const;
    QTime get_heure_fin() const;
    unsigned int get_seuil() const;
    unsigned int get_duree() const;


private:
    unsigned int m_id_conf_as;
    unsigned int m_seuil;
    unsigned int m_duree;
    QTime m_heure_debut;
    QTime m_heure_fin;
    bool m_debug;
};

#endif // CONFANOMALIESONORE_H
