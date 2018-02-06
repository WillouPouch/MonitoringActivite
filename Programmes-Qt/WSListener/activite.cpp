#include "activite.h"

Activite::Activite(QDateTime date_debut, QDateTime date_fin, QString type_activite, unsigned int id_activite, bool debug):
    m_id_activite(id_activite),
    m_date_debut(date_debut),
    m_date_fin(date_fin),
    m_type_activite(type_activite),
    m_debug(debug){

}

Activite::Activite(){

}

unsigned int Activite::get_id_activite() const{
    return m_id_activite;
}

QDateTime Activite::get_date_debut() const{
    return m_date_debut;
}

QDateTime Activite::get_date_fin() const{
    return m_date_fin;
}

QString Activite::get_type_activite() const{
    return m_type_activite;
}
