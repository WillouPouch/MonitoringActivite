#include "confanomaliesonore.h"

ConfAnomalieSonore::ConfAnomalieSonore(QTime heure_debut, QTime heure_fin, unsigned int seuil, unsigned int duree, unsigned int id_conf_as, bool debug):
    m_id_conf_as(id_conf_as),
    m_heure_debut(heure_debut),
    m_heure_fin(heure_fin),
    m_seuil(seuil),
    m_duree(duree),
    m_debug(debug){

}

ConfAnomalieSonore::ConfAnomalieSonore(){

}

unsigned int ConfAnomalieSonore::get_id_conf_as() const{
    return m_id_conf_as;
}

QTime ConfAnomalieSonore::get_heure_debut() const{
    return m_heure_debut;
}

QTime ConfAnomalieSonore::get_heure_fin() const{
    return m_heure_fin;
}

unsigned int ConfAnomalieSonore::get_seuil() const{
    return m_seuil;
}

unsigned int ConfAnomalieSonore::get_duree() const{
    return m_duree;
}
