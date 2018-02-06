#include "anomaliesonore.h"

AnomalieSonore::AnomalieSonore(double niveau, QDateTime date_debut, QDateTime date_fin, unsigned int id_as, bool debug):
    m_id_as(id_as),
    m_niveau(niveau),
    m_date_debut(date_debut),
    m_date_fin(date_fin),
    m_debug(debug){

}

AnomalieSonore::AnomalieSonore(){

}

unsigned int AnomalieSonore::get_id_as() const{
    return m_id_as;
}

double AnomalieSonore::get_niveau() const{
    return m_niveau;
}

QDateTime AnomalieSonore::get_date_debut() const{
    return m_date_debut;
}

QDateTime AnomalieSonore::get_date_fin() const{
    return m_date_fin;
}
