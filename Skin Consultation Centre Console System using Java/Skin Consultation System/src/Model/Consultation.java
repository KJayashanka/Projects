package Model;
import java.util.Date;

public class Consultation {
    public Consultation(Date dateTime, int cost, String notes) {
        this.dateTime = dateTime;
        this.cost = cost;
        this.notes = notes;
    }

    // Fields for the consultation date and time, cost, and notes
    private Date dateTime;
    private int cost;
    private String notes;

    public Date getDateTime() {
        return dateTime;
    }

    public void setDateTime(Date dateTime) {
        this.dateTime = dateTime;
    }

    public int getCost() {
        return cost;
    }

    public void setCost(int cost) {
        this.cost = cost;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
