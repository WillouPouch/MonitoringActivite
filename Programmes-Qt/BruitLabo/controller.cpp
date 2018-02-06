#include "controller.h"

Controller::Controller(QObject *parent, bool debug) :
    QObject(parent)
    ,m_debug(debug){

    m_anomalie = false;
    m_liste_cas = new ListeConfAnomalieSonore();
}

void Controller::db_level(double db_level){

    //Si une anomalie sonore est en cours
    if(m_anomalie){

        //Si le niveau sonore est >= au seuil
        if( db_level >= m_seuil ){

            m_timer_decrease.restart();
            m_tab_db.append(db_level);
        }
        //Si le niveau sonore est < au seuil et qu'il a chuté depuis une 1sec
        else if(db_level < m_seuil && m_timer_decrease.elapsed() >= 1000 ){

            double elapsed_time = (m_timer_anomalie.elapsed()- m_timer_decrease.elapsed()) / 1000.0;

            //Si la durée d'anomalie est atteinte !
            if( elapsed_time >= m_duree ){

                //moyenne du niveau sonore sur la période de l'anomalie
                double mean = 0.0;
                foreach(const double &db, m_tab_db) mean += db;
                mean = mean / m_tab_db.size();

                QJsonObject obj;
                obj.insert("niveau", mean);
                obj.insert("date_debut",  m_start_date.toString(QString("dd/MM/yyyy HH:mm:ss")));
                obj.insert("date_fin", QDateTime::currentDateTime().toString(QString("dd/MM/yyyy HH:mm:ss")));
                QJsonDocument doc(obj);

                if(m_debug) qDebug() << "[Controller::db_level] doc : " << doc.toJson(QJsonDocument::Compact);
                emit send_data(doc.toJson(QJsonDocument::Compact)); //envoi des données
            }

            m_anomalie = false;
            m_tab_db.clear();
        }

    }
    //Sinon
    else {
        //Récupération de la caractérisation d'une anomalie sonore pour l'heure actuelle
        const ConfAnomalieSonore *cas = m_liste_cas->get_cas_time(QTime::currentTime());

        /*Si on est sur une tranche horaire pour laquelle
        une caractérisation d'anomalie sonore existe
        et que le niveau sonore est supérieur au seuil*/
        if(cas!=Q_NULLPTR && db_level > cas->get_seuil()){
            m_duree = cas->get_duree();
            m_seuil = cas->get_seuil();
            m_anomalie = true; //Début d'anomalie
            m_start_date = QDateTime::currentDateTime();
            m_timer_anomalie.restart();
        }
    }

}

