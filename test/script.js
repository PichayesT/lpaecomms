import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

public class MainApplication extends JFrame {
    private JDesktopPane desktopPane;

    public MainApplication() {
        // Initialize the JFrame
        setTitle("Desktop Application");
        setSize(800, 600);
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        setExtendedState(JFrame.MAXIMIZED_BOTH); // Maximize on startup

        // Create the desktop pane
        desktopPane = new JDesktopPane();
        add(desktopPane, BorderLayout.CENTER);

        // Create menu bar
        JMenuBar menuBar = new JMenuBar();
        setJMenuBar(menuBar);  // Attach menu bar

        // "Menu" menu
        JMenu menu = new JMenu("Menu");
        JMenuItem stockManagement = new JMenuItem("Stock Management");
        JMenuItem salesInvoicing = new JMenuItem("Sales and Invoicing");
        JMenu invoices = new JMenu("Invoices");
        JMenu clients = new JMenu("Clients");

        invoices.add(new JMenuItem("Invoices"));
        clients.add(new JMenuItem("Clients"));

        JMenu userManagement = new JMenu("User Management");
        JMenuItem exit = new JMenuItem("Exit");
        exit.addActionListener(e -> System.exit(0));

        // Assemble menu
        menu.add(stockManagement);
        menu.add(salesInvoicing);
        menu.addSeparator();
        menu.add(userManagement);
        menu.addSeparator();
        menu.add(exit);

        menuBar.add(menu);

        // Show the frame
        setVisible(true);
    }

    public static void main(String[] args) {
        new MainApplication();
    }
}
