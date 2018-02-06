#include "tvtracker.h"

using namespace cv;


TvTracker::TvTracker(QObject *parent, bool debug) :
    QObject(parent)
    ,m_tv_on(false)//Part du principe que la tv est off
    ,m_tv_on_old(false)
    ,m_debug(debug){

    moveToThread(&m_thread);
    connect(&m_thread, &QThread::started, this, &TvTracker::start_tv_tracking);
    m_thread.start();
}

#include <QCoreApplication>

void TvTracker::start_tv_tracking(){

    m_cap = VideoCapture("http://192.168.197.21:80/mjpg/video.mjpg");
    //Vidéo test : D:\\PersoHDD\\tv_test1.avi
    // Caméra entrée labo vision (TV) (live: http://192.168.197.21/mjpg/video.mjpg)
    if(!m_cap.isOpened()) return;

    // Traitement de la vidéo
    while(1) {
        Mat img;
        Mat grey_img;
        m_cap >> img; // recuperation de l'image courante
        if (img.empty()) break; //Si la video est terminée

        cvtColor(img, grey_img, CV_BGR2GRAY); // conversion en niveau de gris
        img = grey_img(Rect(1470,660,70,90)); // test sur une partie de la tv

        int hauteur = img.cols;
        int largeur = img.rows;
        int seuil = 25;

        int mean = 0;

        //Parcours des pixels
        for(int i=0; i<hauteur; i++) {
            for(int j=0; j<largeur; j++) mean += img.at<unsigned char>(i,j);
        }
        mean = mean / (hauteur*largeur);

        // Vérification des pixels
        if(mean < seuil) m_tv_on = false;
        else m_tv_on = true;

        //Changement d'état TV
        if(m_tv_on_old!=m_tv_on){

            if(m_tv_on){
                if(m_debug) qDebug() << "TV switched ON";
                emit tv_on();
            }
            else{
                if(m_debug) qDebug() << "TV switched OFF";
                emit tv_off();
            }
             m_tv_on_old = m_tv_on;
        }

        //imshow("Fenetre TV", grey_img);
        //waitKey(1000/m_cap.get(CV_CAP_PROP_FPS)); // 1sec divisé par le nb de FPS
    }

    m_cap.release();
}
